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
     * UserDTO constructor.
     * @param string $username
     * @param string $email
     * @param string $plainPassword
     * @param bool $isAdmin
     */
    public function __construct(string $username, string $email, string $plainPassword, bool $isAdmin)
    {
        $this->username = $username;
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->isAdmin = $isAdmin;
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

}