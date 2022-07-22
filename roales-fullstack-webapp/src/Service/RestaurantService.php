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

namespace App\Service;

use App\Entity\Location;
use App\Entity\Restaurant;
use App\Message\RestaurantsToPersist;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

const RESTAURANT_API_URL = 'https://travel-advisor.p.rapidapi.com/restaurants/list-by-latlng';

/**
 * @access public
 *
 * @version 0.1
 */
class RestaurantService
{
    private TravelAdvisorApiService $advisorApiService;
    private EntityManagerInterface $entityManager;
    private RestaurantRepository $restaurantRepository;
    private MessageBusInterface $bus;
    private LocationService $locationService;

    /**
     * @param TravelAdvisorApiService $advisorApiService
     * @param EntityManagerInterface  $entityManager
     * @param RestaurantRepository    $restaurantRepository
     * @param MessageBusInterface     $bus
     * @param LocationService         $locationService
     */
    public function __construct(TravelAdvisorApiService $advisorApiService, EntityManagerInterface $entityManager, RestaurantRepository $restaurantRepository, MessageBusInterface $bus, LocationService $locationService)
    {
        $this->advisorApiService = $advisorApiService;
        $this->entityManager = $entityManager;
        $this->restaurantRepository = $restaurantRepository;
        $this->bus = $bus;
        $this->locationService = $locationService;
    }

    /**
     * @param Location $location
     *
     * @return Restaurant|null
     */
    public function findOneRestaurantByLocation(Location $location): ?Restaurant
    {
        return $this->restaurantRepository->findOneBy(['location' => $location]);
    }

    /**
     * @param Location $location
     *
     * @return array
     */
    public function findRestaurantsByLocation(Location $location): array
    {
        return $this->restaurantRepository->findBy(['location' => $location]);
    }

    /**
     * @return array
     */
    public function findAllRestaurants(): array
    {
        return $this->restaurantRepository->findRestaurantsWithAddressesPhotos();
    }

    /**
     * @param Location $location
     * @param int      $trApiOffset
     *
     * @return array
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[ArrayShape(["content" => "mixed", "pageNumber" => "float|int"])]
    public function getRestaurantsByLatitudeLongitudeFromTravelAdvisorApi(Location $location, int $trApiOffset): array
    {
        $query = [
            'latitude' => $location->getLat(),
            'longitude' => $location->getLng(),
            'currency' => 'EUR',
            'lang' => 'en_US',
            'lunit' => 'km',
            'open_now' => 'false',
            'distance' => '2',
            'offset' => $trApiOffset,
        ];

        $response = $this->advisorApiService->getResources(
            'GET',
            RESTAURANT_API_URL,
            $query,
        );

        $content = json_decode($response->getContent())->{'data'};
        $pageNumber = intval(json_decode($response->getContent())->{'paging'}->{'total_results'}) / 33;

        return [
            "content" => $content,
            "pageNumber" => intval(round($pageNumber, 0, PHP_ROUND_HALF_DOWN)),
        ];
    }

    /**
     * @param Location $location
     * @param $trApiOffset
     * @param $totalPages
     *
     * @return void
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function persistSomeTravelAdvisorApiRestaurants(Location $location, $trApiOffset, $totalPages): void
    {
        for ($offset = $trApiOffset; $offset < $totalPages; $offset++) {
            $restaurantResponse = $this->getRestaurantsByLatitudeLongitudeFromTravelAdvisorApi($location, $offset);
            $restaurantData = $restaurantResponse['content'];

            for ($i = 0; $i < 9; $i++) {
                $restaurant = new Restaurant();

                set_time_limit(600);

                $restaurant
                    ->setLocation($location)
                    ->setName($restaurantData[$i]->{'name'} ?? "")
                    ->setLatitude(floatval($restaurantData[$i]->{'latitude'} ?? ""))
                    ->setLongitude(floatval($restaurantData[$i]->{'longitude'} ?? ""))
                    ->setNumReviews(intval($restaurantData[$i]->{'num_reviews'} ?? ""))
                    ->setCategoryKey($restaurantData[$i]->{'category'}->{'key'} ?? "")
                    ->setWebsite($restaurantData[$i]->{'website'} ?? "")
                    ->setPhone($restaurantData[$i]->{'phone'} ?? "")
                    ->setAddress($restaurantData[$i]->{'address'} ?? "")
                    ->setPhotoSmallUrl($restaurantData[$i]->{'photo'}->{'images'}->{'small'}->{'url'} ?? "")
                    ->setPhotoThumbnailUrl($restaurantData[$i]->{'photo'}->{'images'}->{'thumbnail'}->{'url'} ?? "")
                    ->setPhotoOriginalUrl($restaurantData[$i]->{'photo'}->{'images'}->{'original'}->{'url'} ?? "")
                    ->setPhotoLargeUrl($restaurantData[$i]->{'photo'}->{'images'}->{'large'}->{'url'} ?? "")
                    ->setPhotoMediumUrl($restaurantData[$i]->{'photo'}->{'images'}->{'medium'}->{'url'} ?? "")
                    ->setWebUrl($restaurantData[$i]->{'web_url'} ?? "")
                    ->setWebReview($restaurantData[$i]->{'write_review'} ?? "")
                    ->setPrice($restaurantData[$i]->{'price'} ?? "")
                    ->setPriceLevel($restaurantData[$i]->{'price_level'} ?? "")
                    ->setRating(floatval($restaurantData[$i]->{'rating'} ?? ""))
                    ->setSlug(preg_replace('/\s+/', '', $restaurant->getName().$restaurant->getAddress()));

                $this->entityManager->persist($restaurant);
            }
        }
        $this->entityManager->flush();
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function persistParisNewYorkTokyoRestaurantsFromTravelAdvisorApi(): void
    {
        $locations = [new Location(), new Location(), new Location()];

        // PARIS
        $locations[0] = $this->locationService->findOneLocationByCityName('Paris');

        // NEW YORK
        $locations[1] = $this->locationService->findOneLocationByCityName('New York');

        // TOKYO
        $locations[2] = $this->locationService->findOneLocationByCityName('Tokyo');

        $this->persistSomeTravelAdvisorApiRestaurants($locations[0], 1, 2);

        for ($i = 0; $i < count($locations); $i++) {
            $this->bus->dispatch(new RestaurantsToPersist($locations[$i]));
        }
    }
}
