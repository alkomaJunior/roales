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

namespace App\MessageHandler;

use App\Message\RestaurantsToPersist;
use App\Service\RestaurantService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @access public
 *
 * @version 0.1
 */
#[AsMessageHandler]
class RestaurantsToPersistHandler
{
    private RestaurantService $restaurantService;

    /**
     * @param RestaurantService $restaurantService
     */
    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    /**
     * @param RestaurantsToPersist $restaurantsToPersist
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function __invoke(RestaurantsToPersist $restaurantsToPersist): void
    {
        $this->restaurantService->persistSomeTravelAdvisorApiRestaurants(
            $restaurantsToPersist->getLocation(),
            2,
            10
        );
    }
}
