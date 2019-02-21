<?php


namespace App\Exception;


class InvalidRoleException extends \Exception
{
    private const MESSAGE = 'The role %s is not a valid role.';

    /**
     * InvalidRoleException constructor.
     */
    public function __construct(string $role)
    {
        parent::__construct(sprintf(self::MESSAGE, $role));
    }


}