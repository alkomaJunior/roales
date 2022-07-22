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

use App\Message\AttractionsToPersist;
use App\Service\AttractionService;
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
class AttractionsToPersistHandler
{
    private AttractionService $attractionService;

    /**
     * @param AttractionService $attractionService
     */
    public function __construct(AttractionService $attractionService)
    {
        $this->attractionService = $attractionService;
    }

    /**
     * @param AttractionsToPersist $attractionsToPersist
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function __invoke(AttractionsToPersist $attractionsToPersist): void
    {
        $this->attractionService->persistSomeTravelAdvisorApiAttractions(
            $attractionsToPersist->getLocation(),
            2,
            6
        );
    }
}
