<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\ValueObject\KeyPair;
use App\Entity\ValueObject\PasswordEncoded;
use App\Exception\UserAlreadyExistsException;
use App\Exception\UserNotFoundException;
use App\Mail\MailSender;
use App\Repository\UserRepositoryInterface;
use App\Service\DTO\ChangePasswordWithTokenDTO;
use App\Service\DTO\RecoveryPasswordDTO;
use App\Service\DTO\UserDTO;

class SecurityService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var MailSender
     */
    private $mailSender;

    /**
     * SecurityService constructor.
     * @param UserRepositoryInterface $userRepository
     * @param MailSender $mailSender
     */
    public function __construct(UserRepositoryInterface $userRepository, MailSender $mailSender)
    {
        $this->userRepository = $userRepository;
        $this->mailSender = $mailSender;
    }

    /**
     * @param RecoveryPasswordDTO $recoveryPasswordDTO
     * @throws \Exception
     */
    public function recoveryPassword(RecoveryPasswordDTO $recoveryPasswordDTO): void
    {
        $user = $this->userRepository->findByEmail($recoveryPasswordDTO->getEmail());

        if(empty($user)) {
            return;
        }

        $user->requestNewPassword();
        $this->userRepository->save($user);

        try {
            $this->mailSender->sendRecoveryPasswordMail($user);
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }

    }

    /**
     * @param ChangePasswordWithTokenDTO $changePasswordWithTokenDTO
     * @throws UserNotFoundException
     */
    public function changePasswordWithToken(ChangePasswordWithTokenDTO $changePasswordWithTokenDTO): void
    {
        $user = $this->userRepository->findByToken($changePasswordWithTokenDTO->getToken());

        if(empty($user)) {
            throw new UserNotFoundException($changePasswordWithTokenDTO->getToken());
        }

        $user->changePassword($changePasswordWithTokenDTO->getNewPassword());

        $this->userRepository->save($user);
    }

    /**
     * @param UserDTO $userDTO
     * @throws UserAlreadyExistsException
     * @throws \App\Exception\InvalidRoleException
     */
    public function registerUser(UserDTO $userDTO): void
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

        $user = new User($userDTO->getUsername(), $userDTO->getEmail(), $password, $roles, $keyPair, true);

        $this->userRepository->save($user);

        $this->mailSender->sendActivateAccountMail($user);
    }

    /**
     * @param string $token
     * @throws UserNotFoundException
     */
    public function activateAccount(string $token): void
    {
        $user = $this->userRepository->findByActivationToken($token);

        if(empty($user)) {
            throw new UserNotFoundException($token);
        }

        $user->enable();

        $this->userRepository->save($user);
    }
}