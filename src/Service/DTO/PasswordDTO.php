<?php

namespace App\Service\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordDTO
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=40)
     */
    private $name = '';

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $username = '';

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $password = '';

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    private $url = '';

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $key = '';

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
    public function getName(): string
    {
        return $this->name;
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @param string $key
     */
    public function setKey(?string $key): void
    {
        $this->key = $key ?: '';
    }

}