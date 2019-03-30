<?php

namespace App\Exception;

class PasswordKeyNotFoundException extends \Exception
{
    private const MESSAGE = 'The key with id %s was not found';

    public function __construct(int $id)
    {
        parent::__construct(sprintf(self::MESSAGE, $id));
    }
}