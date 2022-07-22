<?php

/** @noinspection PhpPropertyOnlyWrittenInspection */

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

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\CommonTrait;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @access public
 *
 * @version 0.1
 */
#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
#[ORM\Table(name: 'restaurants')]
#[ApiResource]
class Restaurant
{
    use CommonTrait;

    #[ORM\ManyToOne(targetEntity: Location::class, cascade: ["persist"], inversedBy: 'restaurants')]
    private ?Location $location;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $price;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $priceLevel;


    /**
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param Location|null $location
     *
     * @return $this
     */
    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string $price
     *
     * @return $this
     */
    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPriceLevel(): ?string
    {
        return $this->priceLevel;
    }

    /**
     * @param string|null $priceLevel
     *
     * @return $this
     */
    public function setPriceLevel(?string $priceLevel): self
    {
        $this->priceLevel = $priceLevel;

        return $this;
    }
}
