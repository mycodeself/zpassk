<?php

namespace App\Entity;

class PasswordKey
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Password
     */
    private $password;

    /**
     * @var string
     */
    private $key;

    /**
     * PasswordKey constructor.
     * @param User $user
     * @param Password $password
     * @param string $key
     */
    public function __construct(User $user, Password $password, string $key)
    {
        $this->user = $user;
        $this->password = $password;
        $this->key = $key;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    public function __toString(): string
    {
        return $this->password->getName() . ' - ' . $this->password->getUrl();
    }


}