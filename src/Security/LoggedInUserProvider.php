<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LoggedInUserProvider
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * LoggedInUserProvider constructor.
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @return User
     */
    public function getUser(): User
    {
        $token = $this->tokenStorage->getToken();
        /** @var AuthUser $authUser */
        $authUser = $token->getUser();

        return $authUser->getUser();
    }
}