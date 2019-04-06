<?php

namespace App\Repository;

use App\Entity\PasswordKey;
use App\Entity\User;

interface PasswordKeyRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $passwordId, int $userId): ?PasswordKey;

    public function getById(int $passwordId, int $userId): PasswordKey;

    public function save(PasswordKey $key): void;

    public function delete(PasswordKey $key): void;

    public function findAllByOwnerGroupedByPassword(User $owner): array;

    public function findSharedForUser(User $user): array;

    public function getByOwnerAndPasswordId(User $user, int $passwordId): PasswordKey;

    public function findByPasswordIdExcludeUser(int $id, User $user): array;
}