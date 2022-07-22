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
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;

const LOCATION_API_URL = 'https://travel-advisor.p.rapidapi.com/locations/v2/auto-complete';

/**
 * @access public
 *
 * @version 0.1
 */
class LocationService
{
    private LocationRepository $locationRepository;
    private EntityManagerInterface $entityManager;
    private TravelAdvisorApiService $advisorApiService;

    /**
     * @param LocationRepository      $locationRepository
     * @param EntityManagerInterface  $entityManager
     * @param TravelAdvisorApiService $advisorApiService
     */
    public function __construct(LocationRepository $locationRepository, EntityManagerInterface $entityManager, TravelAdvisorApiService $advisorApiService)
    {
        $this->locationRepository = $locationRepository;
        $this->entityManager = $entityManager;
        $this->advisorApiService = $advisorApiService;
    }

    /**
     * @param $city
     *
     * @return Location|null
     */
    public function findOneLocationByCityName($city): ?Location
    {
        return $this->locationRepository->findOneBy(['city' => $city]);
    }

    /**
     * @param $city
     *
     * @return Location|null
     */
    public function findOneLocationByAdminName($city): ?Location
    {
        return $this->locationRepository->findOneBy(['adminName' => $city]);
    }

    /**
     * @param float $lat
     * @param float $lng
     *
     * @return Location|null
     */
    public function updateNearbyLocation(float  $lat, float $lng): ?Location
    {
        $nearbyLocation = $this->locationRepository->findOneBy(['slug' => 'nearby']);

        $nearbyLocation->setLat($lat);
        $nearbyLocation->setLng($lng);

        $this->entityManager->flush();

        return $nearbyLocation;
    }
}
