<?php

/**
 * ROALES
 *
 * This file is part of ROALES.
 *
 * ROALES is free road map trip web app: you can redistribute it and/or modify
 * it under the terms of the MIT License.
 *
 * @copyright   Copyright ROALES
 *
 * @license     MIT License
 */

namespace App\Controller;

use App\Entity\Location;
use App\Form\ContactType;
use App\Form\HomeSearchType;
use App\Message\AttractionsToPersist;
use App\Message\HotelsToPersist;
use App\Message\RestaurantsToPersist;
use App\Security\EmailVerifier;
use App\Service\AttractionService;
use App\Service\HotelService;
use App\Service\LocationService;
use App\Service\RestaurantService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @access public
 *
 * @version 0.1
 */
class AppCoreController extends AbstractController
{
    private LocationService $locationService;
    private AttractionService $attractionService;
    private RestaurantService $restaurantService;
    private HotelService $hotelService;
    private MessageBusInterface $bus;
    private EmailVerifier $emailVerifier;

    /**
     * @param LocationService     $locationService
     * @param AttractionService   $attractionService
     * @param RestaurantService   $restaurantService
     * @param HotelService        $hotelService
     * @param MessageBusInterface $bus
     * @param EmailVerifier       $emailVerifier
     */
    public function __construct(LocationService $locationService, AttractionService $attractionService, RestaurantService $restaurantService, HotelService $hotelService, MessageBusInterface $bus, EmailVerifier $emailVerifier)
    {
        $this->locationService = $locationService;
        $this->attractionService = $attractionService;
        $this->restaurantService = $restaurantService;
        $this->hotelService = $hotelService;
        $this->bus = $bus;
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[Route('/', name: 'home')]
    public function home(Request $request): Response
    {
        $location = new Location();
        $homeSearchLocationForm = $this->createForm(HomeSearchType::class, $location, [
            'validation_groups' => ['home_search'],
        ]);

        $homeSearchLocationForm->handleRequest($request);

        if ($homeSearchLocationForm->isSubmitted() && $homeSearchLocationForm->isValid()) {
            /* Here we found a location related to the user request */
            $userLocation = $this->locationService->findOneLocationByCityName($location->getCity()) ??
                $this->locationService->findOneLocationByAdminName($location->getCity());

            /* If we found no location we simply redirect to all the restaurants index */
            if (null === $userLocation) {
                return $this->redirectToRoute('home_search_result', ['slug' => 'Tokyo1'], Response::HTTP_SEE_OTHER);
            }

            /* If there is a single restaurant with the city in question in our db */
            $ourRestaurants = $this->restaurantService->findOneRestaurantByLocation($userLocation);
            $ourAttractions = $this->attractionService->findOneAttractionByLocation($userLocation);
            $ourHotels = $this->hotelService->findOneHotelByLocation($userLocation);

            /* If not persist all the restaurant according to that city from the API in two times by doing async */
            if (null === $ourRestaurants) {
                /* The idea is that we push some restaurants to mercure and let the store process done by messenger  */
                $this->restaurantService->persistSomeTravelAdvisorApiRestaurants($userLocation, 1, 2);
                $this->bus->dispatch(new RestaurantsToPersist($userLocation));
            }

            if (null === $ourAttractions) {
                /* The idea is that we push some restaurants to mercure and let the store process done by messenger  */
                $this->attractionService->persistSomeTravelAdvisorApiAttractions($userLocation, 1, 2);
                $this->bus->dispatch(new AttractionsToPersist($userLocation));
            }

            if (null === $ourHotels) {
                /* The idea is that we push some restaurants to mercure and let the store process done by messenger  */
                $this->hotelService->persistSomeTravelAdvisorApiHotels($userLocation, 1, 2);
                $this->bus->dispatch(new HotelsToPersist($userLocation));
            }

            return $this->redirectToRoute('home_search_result', ['slug' => $userLocation->getSlug()], Response::HTTP_SEE_OTHER);
        }

        $restaurants = $this->restaurantService->findAllRestaurants();
        $hotels = $this->hotelService->findAllHotels();
        $attractions = $this->attractionService->findAllAttractions();

        return $this->renderForm('app_core/home.stream.html.twig', [
            'homeSearchLocationForm' => $homeSearchLocationForm,
            'restaurants' => [
                $restaurants[0],
                $restaurants[1],
                $restaurants[2],
            ],
            'hotels' => [
                $hotels[0],
                $hotels[1],
                $hotels[2],
            ],
        ]);
    }

    /**
     * @param Location $location
     *
     * @return Response
     */
    #[Route('/search-result/{slug}', name: 'home_search_result')]
    public function searchResult(Location $location): Response
    {
        $restaurants = $this->restaurantService->findRestaurantsByLocation($location);
        $hotels = $this->hotelService->findHotelsByLocation($location);
        $attractions = $this->attractionService->findAttractionsByLocation($location);

        return $this->render('app_core/searchResult.html.twig', [
            'location' => $location,
            'restaurants' => [
                $restaurants[0],
                $restaurants[1],
                $restaurants[2],
            ],
            'hotels' => [
                $hotels[0],
                $hotels[1],
                $hotels[2],
            ],
            'attractions' => [
                $attractions[0],
                $attractions[1],
                $attractions[2],
            ],
        ]);
    }


    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[Route('/nearby/{lat}/{lng}', name: 'home_nearby_result', methods: ['GET'])]
    public function nearbyResult(Request $request): Response
    {
        $location = $this->locationService->updateNearbyLocation(floatval($request->get('lat')), floatval($request->get('lng')));

        $this->restaurantService->persistSomeTravelAdvisorApiRestaurants($location, 1, 2);
        $this->attractionService->persistSomeTravelAdvisorApiAttractions($location, 1, 2);
        $this->hotelService->persistSomeTravelAdvisorApiHotels($location, 1, 2);

        $restaurants = $this->restaurantService->findRestaurantsByLocation($location);
        $hotels = $this->hotelService->findHotelsByLocation($location);
        $attractions = $this->attractionService->findAttractionsByLocation($location);

        return $this->render('app_core/searchResult.html.twig', [
            'location' => $location,
            'restaurants' => [
                $restaurants[0],
                $restaurants[1],
                $restaurants[2],
            ],
            'hotels' => [
                $hotels[0],
                $hotels[1],
                $hotels[2],
            ],
            'attractions' => [
                $attractions[0],
                $attractions[1],
                $attractions[2],
            ],
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/about-us', name: 'home_about_us', methods: ['GET'])]
    public function aboutUs(): Response
    {
        return $this->render('app_core/about.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/contact-us', name: 'home_contact_us', methods: ['GET', 'POST'])]
    public function contactUs(Request $request): Response
    {
        $contactForm = $this->createForm(ContactType::class);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $data = $contactForm->getData();
            $this->emailVerifier->sendEmailSimple(
                (new TemplatedEmail())
                    ->from(new Address('totobytata@protonmail.com', $data['fullName']))
                    ->to('roalesroad@gmail.com')
                    ->cc($data['email'])
                    ->subject($data['subject'])
                    ->htmlTemplate('app_core/contactEmailTemplate.html.twig'),
                $data['description'],
                $data['fullName'],
            );

            $this->addFlash('success', 'Your message was successfully send! team will try to get back to you within 24 hours');

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('app_core/contact.html.twig', [
            'contactForm' => $contactForm,
        ]);
    }
}
