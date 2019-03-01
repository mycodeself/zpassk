<?php

namespace App\Exception;

class GroupNotFoundException extends \Exception
{
    private const MESSAGE = 'The group with id %s was not found';

    public function __construct(int $id)
    {
       parent::__construct(sprintf(self::MESSAGE, $id));
    }
}