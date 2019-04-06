<?php

namespace App\Exception;

class PasswordKeyNotFoundException extends \Exception
{
    private const MESSAGE = 'The key of password id %s and user id %s was not found';

    public function __construct(int $passwordId, int $userId)
    {
        parent::__construct(sprintf(self::MESSAGE, $passwordId, $userId));
    }
}