<?php

namespace App\Entity;

class Identity
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
    private $password;

    /**
     * @var Group
     */
    private $group;

    /**
     * Identity constructor.
     * @param string $username
     * @param string $password
     * @param Group $group
     */
    public function __construct(string $username, string $password, Group $group)
    {
        $this->username = $username;
        $this->password = $password;
        $this->group = $group;
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
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

}