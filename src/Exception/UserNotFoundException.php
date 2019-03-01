<?php

namespace App\Exception;

class UserNotFoundException extends \Exception
{
    private const MESSAGE = 'The user %s was not found';

    public function __construct(string $group)
    {
       parent::__construct(sprintf(self::MESSAGE, $group));
    }
}