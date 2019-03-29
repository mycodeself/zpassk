<?php


namespace App\Service\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=100)
     */
    private $username;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(checkMX = true, mode="strict")
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=150)
     */
    private $plainPassword;

    /**
     * @var bool
     */
    private $isAdmin;

    /**
     * @var string|null
     * @Assert\NotBlank(message="An error has occurred")
     */
    private $publicKey;

    /**
     * @var string|null
     * @Assert\NotBlank(message="An error has occurred")
     */
    private $privateKey;

    /**
     * UserDTO constructor.
     * @param string $username
     * @param string $email
     * @param string $plainPassword
     * @param bool $isAdmin
     * @param string|null $publicKey
     * @param string|null $privateKey
     */
    public function __construct(
        string $username,
        string $email,
        string $plainPassword,
        bool $isAdmin,
        ?string $publicKey,
        ?string $privateKey
    )
    {
        $this->username = $username;
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->isAdmin = $isAdmin;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
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
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return string|null
     */
    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    /**
     * @param string|null $publicKey
     */
    public function setPublicKey(?string $publicKey): void
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @return string|null
     */
    public function getPrivateKey(): ?string
    {
        return $this->privateKey;
    }

    /**
     * @param string|null $privateKey
     */
    public function setPrivateKey(?string $privateKey): void
    {
        $this->privateKey = $privateKey;
    }

}