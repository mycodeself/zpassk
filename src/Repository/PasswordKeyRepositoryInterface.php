<?php

namespace App\Repository;

use App\Entity\Password;
use App\Entity\PasswordKey;
use App\Entity\User;

interface PasswordKeyRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?PasswordKey;

    public function getById(int $id): PasswordKey;

    public function save(PasswordKey $key): void;

    public function findAllByOwner(User $owner): array;

    public function findSharedForUser(User $user): array;

    public function getByOwnerAndPasswordId(User $user, int $passwordId): PasswordKey;

    public function findByPasswordIdExcludeUser(int $id, User $user): array;
}