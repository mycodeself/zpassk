<?php


namespace App\Service;

use App\Entity\User;
use App\Entity\ValueObject\KeyPair;
use App\Entity\ValueObject\PasswordEncoded;
use App\Exception\UserAlreadyExistsException;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepositoryInterface;
use App\Service\DTO\UpdateUserDTO;
use App\Service\DTO\UserDTO;

class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UserService constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserDTO $userDTO
     * @throws UserAlreadyExistsException
     * @throws \App\Exception\InvalidRoleException
     */
    public function create(UserDTO $userDTO): void
    {
        if($this->userRepository->findByUsername($userDTO->getUsername())) {
            throw new UserAlreadyExistsException();
        }

        if($this->userRepository->findByEmail($userDTO->getEmail())) {
            throw new UserAlreadyExistsException();
        }

        $password = new PasswordEncoded($userDTO->getPlainPassword());
        $roles = $userDTO->isAdmin() ? ['ROLE_ADMIN'] : ['ROLE_USER'];
        $keyPair = new KeyPair($userDTO->getPrivateKey(), $userDTO->getPublicKey());

        $user = new User($userDTO->getUsername(), $userDTO->getEmail(), $password, $roles, $keyPair);

        $this->userRepository->save($user);
    }

    /**
     * @param UpdateUserDTO $updateUser
     * @throws UserNotFoundException
     * @throws UserAlreadyExistsException
     */
    public function update(UpdateUserDTO $updateUser): void
    {
        $save = false;
        $user = $this->userRepository->getById($updateUser->getId());
        if($user->getEmail() !== $updateUser->getEmail()) {
            if($this->userRepository->findByEmail($updateUser->getEmail())) {
                throw new UserAlreadyExistsException();
            }

            $user->changeEmail($updateUser->getEmail());
            $save = true;
        }

        if($user->isEnabled() !== $updateUser->isEnabled()) {
            if($user->isEnabled()) {
                $user->disable();
            } else {
                $user->enable();
            }

            $save = true;
        }

        if($user->isAdmin() !== $updateUser->isAdmin()) {
            if($updateUser->isAdmin()) {
                $user->grantAdmin();
            } else {
                $user->revokeAdmin();
            }

            $save = true;
        }

        if($save) {
            $this->userRepository->save($user);
        }

    }

}