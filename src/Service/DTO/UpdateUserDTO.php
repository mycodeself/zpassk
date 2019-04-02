<?php

namespace App\Service\DTO;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserDTO
{
    /**
     * @var int
     * @Assert\NotNull()
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(checkMX = true, mode="strict")
     */
    private $email;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * UpdateUserDTO constructor.
     * @param int $id
     * @param string $email
     * @param bool $enabled
     */
    public function __construct(int $id, string $email, bool $enabled)
    {
        $this->id = $id;
        $this->email = $email;
        $this->enabled = $enabled;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return UpdateUserDTO
     */
    public static function fromUser(User $user): self
    {
        return new self($user->getId(), $user->getEmail(), $user->isEnabled());
    }
}