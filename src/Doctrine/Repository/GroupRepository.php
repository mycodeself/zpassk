<?php

namespace App\Doctrine\Repository;

use App\Entity\Group;
use App\Entity\User;
use App\Exception\GroupNotFoundException;
use App\Repository\GroupRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository implements GroupRepositoryInterface
{

    /**
     * @return array
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @param int $id
     * @return Group
     */
    public function findById(int $id): Group
    {
        /** @var Group $group */
        $group = $this->find($id);

        return $group;
    }

    /**
     * @param int $id
     * @return Group
     * @throws GroupNotFoundException
     */
    public function getById(int $id): Group
    {
        $group = $this->findById($id);

        if(empty($group)) {
            throw new GroupNotFoundException($id);
        }

        return $group;
    }

    /**
     * @param Group $group
     */
    public function save(Group $group): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($group);
        $entityManager->flush();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function findAllByOwner(User $user): array
    {
        return $this->findBy(['owner' => $user], ['name' => 'ASC']);
    }
}