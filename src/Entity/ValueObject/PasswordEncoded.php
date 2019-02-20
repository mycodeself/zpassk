<?php

namespace App\Entity\ValueObject;

class PasswordEncoded
{
    /**
     * @var string
     */
    private $value;

    /**
     * PasswordEncoded constructor.
     * @param $value
     */
    public function __construct(string $plainPassword)
    {
        $this->value = $this->hash($plainPassword);
    }

    /**
     * @param string $plainPassword
     * @return string
     */
    private function hash(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    /**
     * @param string $plainPassword
     * @return bool
     */
    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->value());
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }

}