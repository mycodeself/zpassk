<?php

namespace App\Service\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordWithTokenDTO
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $token;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=150)
     */
    private $newPassword;

    /**
     * ChangePasswordDTO constructor.
     * @param string $token
     * @param string $newPassword
     */
    public function __construct(string $token, string $newPassword)
    {
        $this->token = $token;
        $this->newPassword = $newPassword;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @param string $newPassword
     */
    public function setNewPassword(string $newPassword): void
    {
        $this->newPassword = $newPassword;
    }





}