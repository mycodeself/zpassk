<?php

namespace App\Entity;

class Key
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
    private $value;

    /**
     * Key constructor.
     * @param User $user
     * @param Password $password
     * @param string $value
     */
    public function __construct(User $user, Password $password, string $value)
    {
        $this->user = $user;
        $this->password = $password;
        $this->value = $value;
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
    public function getValue(): string
    {
        return $this->value;
    }

}