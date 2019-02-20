<?php

namespace App\Entity;

use App\Entity\ValueObject\PasswordEncoded;
use DateTimeInterface;

class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var PasswordEncoded
     */
    private $password;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var DateTimeInterface|null
     */
    private $passwordRequestedAt;

    /**
     * @var array
     */
    private $roles;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return PasswordEncoded
     */
    public function getPassword(): PasswordEncoded
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPasswordRequestedAt(): ?DateTimeInterface
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }


}