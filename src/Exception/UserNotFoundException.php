<?php

namespace App\Exception;

class UserNotFoundException extends \Exception
{
    private const MESSAGE = 'THe user %s was not found';

    /**
     * UserNotFoundException constructor.
     * @param string $user
     */
    public function __construct(string $user)
    {
       parent::__construct(sprintf(self::MESSAGE, $user));
    }


}