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
use App\Entity\Restaurant;
use App\Service\PaginateService;
use App\Service\RestaurantService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
class RestaurantController extends AbstractController
{
    private RestaurantService $restaurantService;
    private PaginateService $paginateService;

    /**
     * @param RestaurantService $restaurantService
     * @param PaginateService   $paginateService
     */
    public function __construct(RestaurantService $restaurantService, PaginateService $paginateService)
    {
        $this->restaurantService = $restaurantService;
        $this->paginateService = $paginateService;
    }

    /**
     * @return Response
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[Route('/restaurants', name: 'app_restaurant_index', methods: ['GET'])]
    public function restaurantsIndex(): Response
    {
        /* Test if there is a single restaurant in our db if not execute the get selected method */
        if ([] === $this->restaurantService->findAllRestaurants()) {
            $this->restaurantService->persistParisNewYorkTokyoRestaurantsFromTravelAdvisorApi();
        }

        $restaurants = $this->paginateService->paginateResource(
            $this->restaurantService->findAllRestaurants(),
            9
        );

        return $this->render('restaurant/index.html.twig', [
            'restaurantsLength' => count($this->restaurantService->findAllRestaurants()),
            'restaurants' => $restaurants,
        ]);
    }

    /**
     * @param Restaurant $restaurant
     *
     * @return Response
     *
     * @throws Exception
     */
    #[Route('/restaurants/{slug}', name: 'app_restaurant_details', methods: ['GET'])]
    public function restaurantsDetails(Restaurant $restaurant) : Response
    {
        $restaurants = $this->restaurantService->findAllRestaurants();

        return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant,
            'restaurants' => [
                $restaurants[0],
                $restaurants[random_int(10, 13)],
                $restaurants[3],
            ],
        ]);
    }
}
