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

namespace App\Entity\Traits;

use App\Entity\Attraction;
use App\Entity\Hotel;
use App\Entity\Restaurant;
use Doctrine\ORM\Mapping as ORM;

/**
 * @access public
 *
 * @version 0.1
 */
trait CommonTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[ORM\Column(type: 'float')]
    private ?float $latitude;

    #[ORM\Column(type: 'float')]
    private ?float $longitude;

    #[ORM\Column(type: 'integer')]
    private ?int $numReviews;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photoSmallUrl;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photoThumbnailUrl;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photoOriginalUrl;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photoLargeUrl;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photoMediumUrl;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $categoryKey;

    #[ORM\Column(type: 'float', length: 255, nullable: true)]
    private ?float $rating;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $slug;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $website;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $address;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $phone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $webUrl;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $webReview;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isFavorite;

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumReviews(): ?int
    {
        return $this->numReviews;
    }

    /**
     * @param int $numReviews
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setNumReviews(int $numReviews): self
    {
        $this->numReviews = $numReviews;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhotoSmallUrl(): ?string
    {
        return $this->photoSmallUrl;
    }

    /**
     * @param string|null $photoSmallUrl
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setPhotoSmallUrl(?string $photoSmallUrl): self
    {
        $this->photoSmallUrl = $photoSmallUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhotoThumbnailUrl(): ?string
    {
        return $this->photoThumbnailUrl;
    }

    /**
     * @param string|null $photoThumbnailUrl
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setPhotoThumbnailUrl(?string $photoThumbnailUrl): self
    {
        $this->photoThumbnailUrl = $photoThumbnailUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhotoOriginalUrl(): ?string
    {
        return $this->photoOriginalUrl;
    }

    /**
     * @param string|null $photoOriginalUrl
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setPhotoOriginalUrl(?string $photoOriginalUrl): self
    {
        $this->photoOriginalUrl = $photoOriginalUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhotoLargeUrl(): ?string
    {
        return $this->photoLargeUrl;
    }

    /**
     * @param string|null $photoLargeUrl
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setPhotoLargeUrl(?string $photoLargeUrl): self
    {
        $this->photoLargeUrl = $photoLargeUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhotoMediumUrl(): ?string
    {
        return $this->photoMediumUrl;
    }

    /**
     * @param string|null $photoMediumUrl
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setPhotoMediumUrl(?string $photoMediumUrl): self
    {
        $this->photoMediumUrl = $photoMediumUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategoryKey(): ?string
    {
        return $this->categoryKey;
    }

    /**
     * @param string $categoryKey
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setCategoryKey(string $categoryKey): self
    {
        $this->categoryKey = $categoryKey;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getRating(): ?float
    {
        return $this->rating;
    }

    /**
     * @param float|null $rating
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

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
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param string|null $website
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWebUrl(): ?string
    {
        return $this->webUrl;
    }

    /**
     * @param string|null $webUrl
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setWebUrl(?string $webUrl): self
    {
        $this->webUrl = $webUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWebReview(): ?string
    {
        return $this->webReview;
    }

    /**
     * @param string|null $webReview
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setWebReview(?string $webReview): self
    {
        $this->webReview = $webReview;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    /**
     * @param bool|null $isFavorite
     *
     * @return CommonTrait|Attraction|Hotel|Restaurant
     */
    public function setIsFavorite(?bool $isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }
}
