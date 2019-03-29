<?php

namespace App\Entity;

class Password
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $url;

    /**
     * @var User
     */
    private $owner;

    /**
     * Password constructor.
     * @param string $name
     * @param string $username
     * @param string $password
     * @param string $url
     * @param User $owner
     */
    public function __construct(string $name, string $username, string $password, string $url, User $owner)
    {
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
        $this->url = $url;
        $this->owner = $owner;
    }

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
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

}