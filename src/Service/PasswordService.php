<?php

namespace App\Service;

use App\Entity\PasswordKey;
use App\Entity\Password;
use App\Repository\PasswordKeyRepositoryInterface;
use App\Security\LoggedInUserProvider;
use App\Service\DTO\PasswordDTO;

class PasswordService
{
    /**
     * @var PasswordKeyRepositoryInterface
     */
    private $passwordKeyRepository;

    /**
     * @var LoggedInUserProvider
     */
    private $loggedInUserProvider;

    /**
     * PasswordService constructor.
     * @param PasswordKeyRepositoryInterface $passwordKeyRepository
     * @param LoggedInUserProvider $loggedInUserProvider
     */
    public function __construct(PasswordKeyRepositoryInterface $passwordKeyRepository, LoggedInUserProvider $loggedInUserProvider)
    {
        $this->passwordKeyRepository = $passwordKeyRepository;
        $this->loggedInUserProvider = $loggedInUserProvider;
    }

    public function create(PasswordDTO $passwordDTO): void
    {
        $user = $this->loggedInUserProvider->getUser();

        $password = new Password(
            $passwordDTO->getName(),
            $passwordDTO->getUsername(),
            $passwordDTO->getPassword(),
            $passwordDTO->getUrl(),
            $user
        );

        $key = new PasswordKey(
            $user,
            $password,
            $passwordDTO->getKey()
        );

        $this->passwordKeyRepository->save($key);
    }

}