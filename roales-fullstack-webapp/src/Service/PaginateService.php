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

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @access public
 *
 * @version 0.1
 */
class PaginateService
{
    private PaginatorInterface $paginator;
    private RequestStack $request;

    /**
     * @param PaginatorInterface $paginator
     * @param RequestStack       $request
     */
    public function __construct(PaginatorInterface $paginator, RequestStack $request)
    {
        $this->paginator = $paginator;
        $this->request = $request;
    }

    /**
     * @param array $ressource
     * @param int   $nbResourcePerPage
     *
     * @return PaginationInterface
     */
    public function paginateResource(array $ressource, int $nbResourcePerPage): PaginationInterface
    {
        return $this->paginator->paginate(
            $ressource,
            $this->request->getCurrentRequest()->get('page', 1),
            $nbResourcePerPage,
        );
    }
}
