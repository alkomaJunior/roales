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

use App\Message\HotelsToPersist;
use App\Service\HotelService;
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
class HotelsToPersistHandler
{
    private HotelService $hotelService;

    /**
     * @param HotelService $hotelService
     */
    public function __construct(HotelService $hotelService)
    {
        $this->hotelService = $hotelService;
    }

    /**
     * @param HotelsToPersist $hotelsToPersist
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function __invoke(HotelsToPersist $hotelsToPersist): void
    {
        $this->hotelService->persistSomeTravelAdvisorApiHotels(
            $hotelsToPersist->getLocation(),
            2,
            6
        );
    }
}
