<?php

namespace App\Repository;

use App\Entity\Password;
use App\Exception\PasswordNotFoundException;

interface PasswordRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Password;

    /**
     * @param int $id
     * @return Password
     * @throws PasswordNotFoundException
     */
    public function getById(int $id): Password;

    public function save(Password $password): void;

    public function delete(Password $password): void;
}