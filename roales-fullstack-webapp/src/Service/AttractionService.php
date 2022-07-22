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

const ATTRACTION_API_URL = 'https://travel-advisor.p.rapidapi.com/attractions/list-by-latlng';

use App\Entity\Attraction;
use App\Entity\Location;
use App\Entity\User;
use App\Message\AttractionsToPersist;
use App\Repository\AttractionRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @access public
 *
 * @version 0.1
 */
class AttractionService
{
    private TravelAdvisorApiService $advisorApiService;
    private EntityManagerInterface $entityManager;
    private AttractionRepository $attractionRepository;
    private MessageBusInterface $bus;
    private LocationService $locationService;

    /**
     * @param TravelAdvisorApiService $advisorApiService
     * @param EntityManagerInterface  $entityManager
     * @param AttractionRepository    $attractionRepository
     * @param MessageBusInterface     $bus
     * @param LocationService         $locationService
     */
    public function __construct(TravelAdvisorApiService $advisorApiService, EntityManagerInterface $entityManager, AttractionRepository $attractionRepository, MessageBusInterface $bus, LocationService $locationService)
    {
        $this->advisorApiService = $advisorApiService;
        $this->entityManager = $entityManager;
        $this->attractionRepository = $attractionRepository;
        $this->bus = $bus;
        $this->locationService = $locationService;
    }

    /**
     * @param Location $location
     *
     * @return Attraction|null
     */
    public function findOneAttractionByLocation(Location $location): ?Attraction
    {
        return $this->attractionRepository->findOneBy(['location' => $location]);
    }

    /**
     * @param User       $user
     * @param Attraction $attraction
     *
     * @return void
     */
    public function addOneUser(User $user, Attraction $attraction): void
    {
        $attraction->addUser($user);

        $this->entityManager->flush();
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public function findFavoriteAttraction(int $userId): array
    {
        return $this->attractionRepository->findByIdJoinedToUser($userId);
    }

    /**
     * @param Location $location
     *
     * @return array
     */
    public function findAttractionsByLocation(Location $location): array
    {
        return $this->attractionRepository->findBy(['location' => $location]);
    }

    /**
     * @return array
     */
    public function findAllAttractions(): array
    {
        return $this->attractionRepository->findAttractionsWithAddressesPhotos();
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
    public function getAttractionsByLatitudeLongitudeFromTravelAdvisorApi(Location $location, int $trApiOffset): array
    {
        $query = [
            'latitude' => $location->getLat(),
            'longitude' => $location->getLng(),
            'currency' => 'EUR',
            'lang' => 'en_US',
            'lunit' => 'km',
            'offset' => $trApiOffset,
        ];

        $response = $this->advisorApiService->getResources(
            'GET',
            ATTRACTION_API_URL,
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
    public function persistSomeTravelAdvisorApiAttractions(Location $location, $trApiOffset, $totalPages): void
    {
        for ($offset = $trApiOffset; $offset < $totalPages; $offset++) {
            $attractionResponse = $this->getAttractionsByLatitudeLongitudeFromTravelAdvisorApi($location, $offset);
            $attractionData = $attractionResponse['content'];

            for ($i = 0; $i < 9; $i++) {
                $attraction = new Attraction();

                set_time_limit(600);

                $attraction
                    ->setLocation($location)
                    ->setName($attractionData[$i]->{'name'} ?? "")
                    ->setLatitude(floatval($attractionData[$i]->{'latitude'} ?? ""))
                    ->setLongitude(floatval($attractionData[$i]->{'longitude'} ?? ""))
                    ->setNumReviews(intval($attractionData[$i]->{'num_reviews'} ?? ""))
                    ->setCategoryKey($attractionData[$i]->{'category'}->{'key'} ?? "")
                    ->setWebsite($attractionData[$i]->{'website'} ?? "")
                    ->setPhone($attractionData[$i]->{'phone'} ?? "")
                    ->setAddress($attractionData[$i]->{'address'} ?? "")
                    ->setPhotoSmallUrl($attractionData[$i]->{'photo'}->{'images'}->{'small'}->{'url'} ?? "")
                    ->setPhotoThumbnailUrl($attractionData[$i]->{'photo'}->{'images'}->{'thumbnail'}->{'url'} ?? "")
                    ->setPhotoOriginalUrl($attractionData[$i]->{'photo'}->{'images'}->{'original'}->{'url'} ?? "")
                    ->setPhotoLargeUrl($attractionData[$i]->{'photo'}->{'images'}->{'large'}->{'url'} ?? "")
                    ->setPhotoMediumUrl($attractionData[$i]->{'photo'}->{'images'}->{'medium'}->{'url'} ?? "")
                    ->setWebUrl($attractionData[$i]->{'web_url'} ?? "")
                    ->setWebReview($attractionData[$i]->{'write_review'} ?? "")
                    ->setRating(floatval($attractionData[$i]->{'rating'} ?? ""))
                    ->setSlug(preg_replace('/\s+/', '', $attraction->getName().$attraction->getAddress()));

                $this->entityManager->persist($attraction);
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
    public function persistParisNewYorkTokyoAttractionsFromTravelAdvisorApi(): void
    {
        $locations = [new Location(), new Location(), new Location()];

        // PARIS
        $locations[0] = $this->locationService->findOneLocationByCityName('Paris');

        // NEW YORK
        $locations[1] = $this->locationService->findOneLocationByCityName('New York');

        // TOKYO
        $locations[2] = $this->locationService->findOneLocationByCityName('Tokyo');

        $this->persistSomeTravelAdvisorApiAttractions($locations[0], 1, 2);

        for ($i = 0; $i < count($locations); $i++) {
            $this->bus->dispatch(new AttractionsToPersist($locations[$i]));
        }
    }
}
