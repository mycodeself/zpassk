<?php

namespace App\Service;

use App\Entity\PasswordKey;
use App\Entity\Password;
use App\Exception\PasswordKeyNotFoundException;
use App\Repository\PasswordKeyRepositoryInterface;
use App\Repository\UserRepositoryInterface;
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
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * PasswordService constructor.
     * @param PasswordKeyRepositoryInterface $passwordKeyRepository
     * @param LoggedInUserProvider $loggedInUserProvider
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        PasswordKeyRepositoryInterface $passwordKeyRepository,
        LoggedInUserProvider $loggedInUserProvider,
        UserRepositoryInterface $userRepository
    )
    {
        $this->passwordKeyRepository = $passwordKeyRepository;
        $this->loggedInUserProvider = $loggedInUserProvider;
        $this->userRepository = $userRepository;
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

    /**
     * @param string $key
     * @param int $userId
     * @param int $passwordId
     * @throws PasswordKeyNotFoundException
     * @throws \App\Exception\UserNotFoundException
     */
    public function share(string $key, int $userId, int $passwordId): void
    {
        $owner = $this->loggedInUserProvider->getUser();

        $ownerPasswordKey = $this->passwordKeyRepository->getByOwnerAndPasswordId($owner, $passwordId);

        if(!$ownerPasswordKey) {
            throw new PasswordKeyNotFoundException($passwordId);
        }

        $user = $this->userRepository->getById($userId);

        $passwordKey = new PasswordKey($user, $ownerPasswordKey->getPassword(), $key);

        $this->passwordKeyRepository->save($passwordKey);
    }
}