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

use App\Entity\Attraction;
use App\Entity\User;
use App\Service\AttractionService;
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
class AttractionController extends AbstractController
{
    private AttractionService $attractionService;
    private PaginateService $paginateService;

    /**
     * @param AttractionService $attractionService
     * @param PaginateService   $paginateService
     */
    public function __construct(AttractionService $attractionService, PaginateService $paginateService)
    {
        $this->attractionService = $attractionService;
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
    #[Route('/attractions', name: 'app_attraction_index', methods: ['GET'])]
    public function attractionIndex(): Response
    {
        /* Test if there is a single restaurant in our db if not execute the get selected method */
        if ([] === $this->attractionService->findAllAttractions()) {
            $this->attractionService->persistParisNewYorkTokyoAttractionsFromTravelAdvisorApi();
        }

        $attractions = $this->paginateService->paginateResource(
            $this->attractionService->findAllAttractions(),
            9
        );

        return $this->render('attraction/index.html.twig', [
            'attractionsLength' => count($this->attractionService->findAllAttractions()),
            'attractions' => $attractions,
        ]);
    }

    /**
     * @param Attraction $attraction
     *
     * @return Response
     *
     * @throws Exception
     */
    #[Route('/attractions/{slug}', name: 'app_attraction_details', methods: ['GET'])]
    public function attractionsDetails(Attraction $attraction) : Response
    {
        $attractions = $this->attractionService->findAllAttractions();

        return $this->render('attraction/show.html.twig', [
            'attraction' => $attraction,
            'attractions' => [
                $attractions[0],
                $attractions[random_int(10, 13)],
                $attractions[3],
            ],
        ]);
    }

    /**
     * @param Attraction $attraction
     *
     * @return Response
     *
     * @throws Exception
     */
    #[Route('/attractions-favorite/{id}', name: 'app_attraction_favorites', methods: ['GET', 'POST'])]
    public function attractionsFavorite(Attraction $attraction) : Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();

        $this->attractionService->addOneUser($user, $attraction);

        return new Response();
    }
}
