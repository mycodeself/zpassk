<?php

namespace App\Repository;

use App\Entity\Password;

interface PasswordRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Password;

    public function getById(int $id): Password;

    public function save(Password $password): void;
}