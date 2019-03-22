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
     * @var string
     * @Assert\Length(min=8, max=150)
     */
    private $newPassword;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * UpdateUserDTO constructor.
     * @param int $id
     * @param string $email
     */
    public function __construct(int $id, string $email, bool $enabled, string $newPassword = '')
    {
        $this->id = $id;
        $this->email = $email;
        $this->enabled = $enabled;
        $this->newPassword = $newPassword;
        $this->privateKey = '';
        $this->publicKey = '';
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
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * @param string $privateKey
     */
    public function setPrivateKey(string $privateKey): void
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @param string $publicKey
     */
    public function setPublicKey(string $publicKey): void
    {
        $this->publicKey = $publicKey;
    }



    /**
     * @return UpdateUserDTO
     */
    public static function fromUser(User $user): self
    {
        return new self($user->getId(), $user->getEmail(), $user->isEnabled());
    }
}