<?php

namespace App\Exception;

class IdentityNotFoundException extends \Exception
{
    private const MESSAGE = 'The identity with id %s was not found';

    public function __construct(int $id)
    {
       parent::__construct(sprintf(self::MESSAGE, $id));
    }
}