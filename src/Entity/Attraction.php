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
use App\Repository\AttractionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @access public
 *
 * @version 0.1
 */
#[ORM\Entity(repositoryClass: AttractionRepository::class)]
#[ApiResource]
#[ORM\Table(name: 'attractions')]
class Attraction
{
    use CommonTrait;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'attractions')]
    private ?Location $location;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'attractions', cascade: ['persist', 'remove'])]
    private mixed $user;

    /**
     *
     */
    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

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
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }
}
