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

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @access public
 *
 * @version 0.1
 */
class TravelAdvisorApiService
{
    private ?string $apiKey;
    private ?string $apiHost;
    private HttpClientInterface $httpClient;

    /**
     * @param $apiKey
     * @param $apiHost
     * @param HttpClientInterface $httpClient
     */
    public function __construct($apiKey, $apiHost, HttpClientInterface $httpClient)
    {
        $this->apiKey = $apiKey;
        $this->apiHost = $apiHost;
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws TransportExceptionInterface
     */
    public function getResources(string $method, string $endpoint, array $query): ResponseInterface
    {
        $headers = [
            'X-RapidAPI-Key' => $this->apiKey,
            'X-RapidAPI-Host' => $this->apiHost,
        ];

        return $this->httpClient->request(
            $method,
            $endpoint,
            [
                'headers' => $headers,
                'query' => $query,
            ]
        );
    }
}
