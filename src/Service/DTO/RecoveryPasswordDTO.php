<?php

namespace App\Service\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RecoveryPasswordDTO
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(checkMX = true, mode="strict")
     */
    private $email;

    /**
     * RequestRecoveryPasswordDTO constructor.
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

}