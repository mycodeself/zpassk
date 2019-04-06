<?php

namespace App\Repository;

use App\Entity\User;
use App\Exception\UserNotFoundException;

interface UserRepositoryInterface
{
    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param string $username
     * @return User
     */
    public function findByUsername(string $username): ?User;

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * @param string $token
     * @return User|null
     */
    public function findByToken(string $token): ?User;

    /**
     * @param string $token
     * @return User|null
     */
    public function findByActivationToken(string $token): ?User;

    /**
     * @param int $id
     * @return User
     */
    public function findById(int $id): User;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function getById(int $id): User;

    /**
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * @param User $user
     * @return array
     */
    public function findAllExcept(User $user): array;

    public function findAllBySharedPasswordIdExcludeUser(int $id, User $user): array;

}