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

namespace App\Message\Traits;

use App\Entity\Location;

/**
 * @access public
 *
 * @version 0.1
 */
trait LocationTrait
{
    private Location $location;

    /**
     * @param Location $location
     */
    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }
}