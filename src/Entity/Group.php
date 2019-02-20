<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Group
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
     * @var User[]|ArrayCollection
     */
    private $users;

    /**
     * @var string
     */
    private $imagePath;

    /**
     * Group constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->users = new ArrayCollection();
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
     * @return User[]|ArrayCollection
     */
    public function getUsers(): array
    {
        return $this->users->toArray();
    }

    /**
     * @return string
     */
    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * @param string $name
     * @param string $imagePath
     */
    public function update(string $name, string $imagePath): void
    {
        $this->name = $name;
        $this->imagePath = $imagePath;
    }

    /**
     * @param User $user
     */
    public function addUser(User $user): void
    {
        $this->users->add($user);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

}