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
     * @var string
     */
    private $newPassword;

    /**
     * UpdateUserDTO constructor.
     * @param int $id
     * @param string $email
     */
    public function __construct(int $id, string $email, string $newPassword = '')
    {
        $this->id = $id;
        $this->email = $email;
        $this->newPassword = $newPassword;
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
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     */
    public function setNewPassword(string $newPassword): void
    {
        $this->newPassword = $newPassword;
    }

    /**
     * @return UpdateUserDTO
     */
    public static function fromUser(User $user): self
    {
        return new self($user->getId(), $user->getEmail());
    }
}