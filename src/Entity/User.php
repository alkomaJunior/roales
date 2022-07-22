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

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Rollerworks\Component\PasswordStrength\Validator\Constraints as RollerworksPassword;

/**
 * @access public
 *
 * @version 0.1
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[NotBlank(message: 'This field should not be blank', groups: ['register'])]
    #[Email(message: 'This file is not a valid email.', groups: ['register'])]
    private ?string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[NotBlank(message: 'This field should not be blank')]
    private string $password;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank(message: 'This field should not be blank', groups: ['register'])]
    private ?string $fullName;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $dateOfBirth;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $gender;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $phoneNumber;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $address;

    #[
        NotBlank(message: 'This field should not be blank', groups: ['register']),
        RollerworksPassword\PasswordStrength(
            groups: ['register'],
            minStrength: 4,
            minLength: 8,
            message: 'Password is not strong enough',
            tooShortMessage: 'Min password is 8'
        ),
        RollerworksPassword\PasswordRequirements(
            groups: ['register'],
            requireLetters: true,
            requireCaseDiff: true,
            requireNumbers: true,
            requireSpecialCharacter: true,
            tooShortMessage: 'Password is too short',
            missingLettersMessage: 'Missing at least on letter in your password',
            requireCaseDiffMessage: 'Missing at least on uppercase letter in your password',
            missingNumbersMessage: 'Missing at least on number in your password',
            missingSpecialCharacterMessage: 'Missing at least on special character in your password'
        ),
        NotCompromisedPassword(
            message: 'Password seems to be compromised',
            groups: ['register']
        )
    ]
    private ?string $plainPassword;

    #[ORM\ManyToMany(targetEntity: Attraction::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private mixed $attractions;

    /**
     *
     */
    public function __construct()
    {
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @return string
     *
     * @see UserInterface
     *
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @return array
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /***
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     *
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * @param bool $isVerified
     *
     * @return $this
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return $this
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateOfBirth(): ?DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    /**
     * @param DateTimeInterface|null $dateOfBirth
     *
     * @return $this
     */
    public function setDateOfBirth(?DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     *
     * @return $this
     */
    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     *
     * @return $this
     */
    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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
     * @return $this
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

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
            $attraction->addUser($this);
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
            $attraction->removeUser($this);
        }

        return $this;
    }
}
