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

use App\Entity\Hotel;
use App\Entity\Location;
use App\Message\HotelsToPersist;
use App\Message\RestaurantsToPersist;
use App\Repository\HotelRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

const HOTEL_API_URL = 'https://travel-advisor.p.rapidapi.com/hotels/list-by-latlng';

/**
 * @access public
 *
 * @version 0.1
 */
class HotelService
{
    private TravelAdvisorApiService $advisorApiService;
    private EntityManagerInterface $entityManager;
    private HotelRepository $hotelRepository;
    private MessageBusInterface $bus;
    private LocationService $locationService;

    /**
     * @param TravelAdvisorApiService $advisorApiService
     * @param EntityManagerInterface  $entityManager
     * @param HotelRepository         $hotelRepository
     * @param MessageBusInterface     $bus
     * @param LocationService         $locationService
     */
    public function __construct(TravelAdvisorApiService $advisorApiService, EntityManagerInterface $entityManager, HotelRepository $hotelRepository, MessageBusInterface $bus, LocationService $locationService)
    {
        $this->advisorApiService = $advisorApiService;
        $this->entityManager = $entityManager;
        $this->hotelRepository = $hotelRepository;
        $this->bus = $bus;
        $this->locationService = $locationService;
    }

    /**
     * @param Location $location
     *
     * @return Hotel|null
     */
    public function findOneHotelByLocation(Location $location): ?Hotel
    {
        return $this->hotelRepository->findOneBy(['location' => $location]);
    }

    /**
     * @param Location $location
     *
     * @return array
     */
    public function findHotelsByLocation(Location $location): array
    {
        return $this->hotelRepository->findBy(['location' => $location]);
    }

    /**
     * @return array
     */
    public function findAllHotels(): array
    {
        return $this->hotelRepository->findHotelsWithAddressesPhotos();
    }

    /**
     * @param Location $location
     * @param DateTime $date
     *
     * @return array
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[ArrayShape(["content" => "mixed", "pageNumber" => "float|int"])]
    public function getHotelsByLatitudeLongitudeFromTravelAdvisorApi(Location $location, DateTime $date): array
    {
        $query = [
            'latitude' => $location->getLat(),
            'longitude' => $location->getLng(),
            'currency' => 'EUR',
            'lang' => 'en_US',
            'hotel_class' => '1,2,3',
            'limit' => '30',
            'adults' => '1',
            'rooms' => '1',
            'child_rm_ages' => '7,10',
            'checkin' => $date->format('Y-m-d'),
            'zff' => '4,6',
            'subcategory' => 'hotel,bb,specialty',
            'nights' => '2',
        ];

        $response = $this->advisorApiService->getResources(
            'GET',
            HOTEL_API_URL,
            $query,
        );

        $content = json_decode($response->getContent())->{'data'};

        return [
            "content" => $content,
        ];
    }

    /**
     * @param Location $location
     * @param int      $dateCounterStart
     * @param int      $dateCounterEnd
     *
     * @return void
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function persistSomeTravelAdvisorApiHotels(Location $location, int $dateCounterStart, int $dateCounterEnd): void
    {
        for ($i = $dateCounterStart; $i < $dateCounterEnd; $i++) {
            $dateTimeInterval = new DateInterval('P'.$i.'D');
            $date = new DateTime();
            $hotelResponse = $this->getHotelsByLatitudeLongitudeFromTravelAdvisorApi($location, $date->add($dateTimeInterval));
            $hotelData = $hotelResponse['content'];

            for ($j = 0; $j < 9; $j++) {
                $hotel = new Hotel();

                set_time_limit(600);

                $hotel
                    ->setLocation($location)
                    ->setName($hotelData[$j]->{'name'} ?? "")
                    ->setLatitude(floatval($hotelData[$j]->{'latitude'} ?? ""))
                    ->setLongitude(floatval($hotelData[$j]->{'longitude'} ?? ""))
                    ->setNumReviews(intval($hotelData[$j]->{'num_reviews'} ?? ""))
                    ->setCategoryKey($hotelData[$j]->{'ranking_category'} ?? "")
                    ->setWebsite("https://www.tripadvisor.com/Hotels")
                    ->setPhone("(302) 555-0107, (302) 555-0208")
                    ->setAddress("".$hotel->getName()." ".$hotel->getLocation()->getCity().", ".$hotel->getLocation()->getCountry())
                    ->setPhotoSmallUrl($hotelData[$j]->{'photo'}->{'images'}->{'small'}->{'url'} ?? "")
                    ->setPhotoThumbnailUrl($hotelData[$j]->{'photo'}->{'images'}->{'thumbnail'}->{'url'} ?? "")
                    ->setPhotoOriginalUrl($hotelData[$j]->{'photo'}->{'images'}->{'original'}->{'url'} ?? "")
                    ->setPhotoLargeUrl($hotelData[$j]->{'photo'}->{'images'}->{'large'}->{'url'} ?? "")
                    ->setPhotoMediumUrl($hotelData[$j]->{'photo'}->{'images'}->{'medium'}->{'url'} ?? "")
                    ->setWebUrl("https://www.tripadvisor.com/Hotels")
                    ->setWebReview("https://www.tripadvisor.com")
                    ->setPrice($hotelData[$j]->{'price'} ?? "")
                    ->setPriceLevel($hotelData[$j]->{'price_level'} ?? "")
                    ->setRating(floatval($hotelData[$j]->{'rating'} ?? ""))
                    ->setSlug(preg_replace('/\s+/', '', $hotel->getName().$hotel->getAddress()));

                $this->entityManager->persist($hotel);
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
    public function persistParisNewYorkTokyoHotelsFromTravelAdvisorApi(): void
    {
        $locations = [new Location(), new Location(), new Location()];

        // PARIS
        $locations[0] = $this->locationService->findOneLocationByCityName('Paris');

        // NEW YORK
        $locations[1] = $this->locationService->findOneLocationByCityName('New York');

        // TOKYO
        $locations[2] = $this->locationService->findOneLocationByCityName('Tokyo');

        $this->persistSomeTravelAdvisorApiHotels($locations[0], 1, 2);

        for ($i = 0; $i < count($locations); $i++) {
            $this->bus->dispatch(new HotelsToPersist($locations[$i]));
        }
    }
}
