<?php

namespace App\Repository;

use App\Entity\Identity;

interface IdentityRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Identity;

    public function getById(int $id): Identity;

    public function save(Identity $group): void;
}