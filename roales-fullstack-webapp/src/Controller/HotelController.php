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

use App\Entity\Hotel;
use App\Service\HotelService;
use App\Service\PaginateService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
class HotelController extends AbstractController
{
    private HotelService $hotelService;
    private PaginateService $paginateService;

    /**
     * @param HotelService    $hotelService
     * @param PaginateService $paginateService
     */
    public function __construct(HotelService $hotelService, PaginateService $paginateService)
    {
        $this->hotelService = $hotelService;
        $this->paginateService = $paginateService;
    }

    /***
     * @return Response
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[Route('/hotels', name: 'app_hotel_index', methods: ['GET'])]
    public function hotelsIndex(): Response
    {
        /* Test if there is a single restaurant in our db if not execute the get selected method */
        if ([] === $this->hotelService->findAllHotels()) {
            $this->hotelService->persistParisNewYorkTokyoHotelsFromTravelAdvisorApi();
        }

        $hotels = $this->paginateService->paginateResource(
            $this->hotelService->findAllHotels(),
            9
        );

        return $this->render('hotel/index.html.twig', [
            'hotelsLength' => count($this->hotelService->findAllHotels()),
            'hotels' => $hotels,
        ]);
    }

    /**
     * @param Hotel $hotel
     *
     * @return Response
     *
     * @throws Exception
     */
    #[Route('/hotels/{slug}', name: 'app_hotel_details', methods: ['GET'])]
    public function restaurantsDetails(Hotel $hotel) : Response
    {
        $hotels = $this->hotelService->findAllHotels();

        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
            'hotels' => [
                $hotels[0],
                $hotels[random_int(10, 13)],
                $hotels[3],
            ],
        ]);
    }
}
