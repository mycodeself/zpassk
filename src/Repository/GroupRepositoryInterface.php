<?php

namespace App\Repository;


use App\Entity\Group;
use App\Entity\User;

interface GroupRepositoryInterface
{
    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Group
     */
    public function findById(int $id): ?Group;

    /**
     * @param int $id
     * @return Group
     */
    public function getById(int $id): Group;

    /**
     * @param Group $group
     */
    public function save(Group $group): void;

    /**
     * @param User $user
     * @return mixed
     */
    public function findAllByOwner(User $user): array;
}