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

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @access public
 *
 * @version 0.1
 */
#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ORM\Table(name: 'locations')]
#[ApiResource(attributes: ["pagination_items_per_page" => 200 ])]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank(message: 'Please input a destination!!!', groups: ['home_search'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?string $city;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank]
    private ?string $cityAscii = "";

    #[ORM\Column(type: 'float')]
    #[NotBlank]
    private ?float $lat;

    #[ORM\Column(type: 'float')]
    #[NotBlank]
    private ?float $lng;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?string $country;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank]
    private ?string $isoTwo;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank]
    private ?string $isoThree;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[NotBlank]
    private ?string $adminName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $capital;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[NotBlank]
    private ?string $population;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $source;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Restaurant::class)]
    private mixed $restaurants;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Hotel::class)]
    private mixed $hotels;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Attraction::class)]
    private mixed $attractions;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $slug;

    /**
     *
     */
    public function __construct()
    {
        $this->restaurants = new ArrayCollection();
        $this->hotels = new ArrayCollection();
        $this->attractions = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCityAscii(): ?string
    {
        return $this->cityAscii;
    }

    /**
     * @param string $cityAscii
     *
     * @return $this
     */
    public function setCityAscii(string $cityAscii): self
    {
        $this->cityAscii = $cityAscii;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     *
     * @return $this
     */
    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLng(): ?float
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     *
     * @return $this
     */
    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIsoTwo(): ?string
    {
        return $this->isoTwo;
    }

    /**
     * @param string $iso2
     *
     * @return $this
     */
    public function setIsoTwo(string $iso2): self
    {
        $this->isoTwo = $iso2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIsoThree(): ?string
    {
        return $this->isoThree;
    }

    /**
     * @param string $iso3
     *
     * @return $this
     */
    public function setIsoThree(string $iso3): self
    {
        $this->isoThree = $iso3;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdminName(): ?string
    {
        return $this->adminName;
    }

    /**
     * @param string $adminName
     *
     * @return $this
     */
    public function setAdminName(string $adminName): self
    {
        $this->adminName = $adminName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCapital(): ?string
    {
        return $this->capital;
    }

    /**
     * @param string|null $capital
     *
     * @return $this
     */
    public function setCapital(?string $capital): self
    {
        $this->capital = $capital;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPopulation(): ?int
    {
        return $this->population;
    }

    /**
     * @param int $population
     *
     * @return $this
     */
    public function setPopulation(int $population): self
    {
        $this->population = $population;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @param string $source
     *
     * @return $this
     */
    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return ?Collection<int, Restaurant>
     */
    public function getRestaurants(): ?Collection
    {
        return $this->restaurants;
    }

    /**
     * @param Restaurant $restaurant
     *
     * @return $this
     */
    public function addRestaurant(Restaurant $restaurant): self
    {
        if (!$this->restaurants->contains($restaurant)) {
            $this->restaurants[] = $restaurant;
            $restaurant->setLocation($this);
        }

        return $this;
    }

    /**
     * @param Restaurant $restaurant
     *
     * @return $this
     */
    public function removeRestaurant(Restaurant $restaurant): self
    {
        if ($this->restaurants->removeElement($restaurant)) {
            // set the owning side to null (unless already changed)
            if ($restaurant->getLocation() === $this) {
                $restaurant->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hotel>
     */
    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    /**
     * @param Hotel $hotel
     *
     * @return $this
     */
    public function addHotel(Hotel $hotel): self
    {
        if (!$this->hotels->contains($hotel)) {
            $this->hotels[] = $hotel;
            $hotel->setLocation($this);
        }

        return $this;
    }

    /**
     * @param Hotel $hotel
     *
     * @return $this
     */
    public function removeHotel(Hotel $hotel): self
    {
        if ($this->hotels->removeElement($hotel)) {
            // set the owning side to null (unless already changed)
            if ($hotel->getLocation() === $this) {
                $hotel->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Attraction>
     */
    public function getAttractions(): Collection
    {
        return $this->attractions;
    }

    /**
     * @param Attraction $attraction
     *
     * @return $this
     */
    public function addAttraction(Attraction $attraction): self
    {
        if (!$this->attractions->contains($attraction)) {
            $this->attractions[] = $attraction;
            $attraction->setLocation($this);
        }

        return $this;
    }

    /**
     * @param Attraction $attraction
     *
     * @return $this
     */
    public function removeAttraction(Attraction $attraction): self
    {
        if ($this->attractions->removeElement($attraction)) {
            // set the owning side to null (unless already changed)
            if ($attraction->getLocation() === $this) {
                $attraction->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
