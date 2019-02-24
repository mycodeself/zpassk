<?php


namespace App\Service;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepositoryInterface;
use App\Service\DTO\ChangePasswordWithTokenDTO;
use App\Service\DTO\RecoveryPasswordDTO;
use App\Service\DTO\UpdateUserDTO;

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

    public function create(User $user): void
    {

        $this->userRepository->save($user);
    }

    /**
     * @param UpdateUserDTO $updateUser
     * @throws UserNotFoundException
     */
    public function update(UpdateUserDTO $updateUser): void
    {
        $save = false;
        $user = $this->userRepository->getById($updateUser->getId());
        if($user->getEmail() !== $updateUser->getEmail()) {
            $user->changeEmail($updateUser->getEmail());
            $save = true;
        }

        if(!empty($updateUser->getNewPassword())) {
            $user->changePassword($updateUser->getNewPassword());
            $save = true;
        }

        if($save) {
            $this->userRepository->save($user);
        }

    }

}