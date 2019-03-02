<?php

namespace App\Doctrine\Repository;

use App\Entity\Identity;
use App\Exception\IdentityNotFoundException;
use App\Repository\IdentityRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class IdentityRepository extends EntityRepository implements IdentityRepositoryInterface
{

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function findById(int $id): ?Identity
    {
        /** @var Identity|null $identity */
        $identity = $this->find($id);

        return $identity;
    }

    public function getById(int $id): Identity
    {
        $identity = $this->findById($id);

        if(empty($identity)) {
            throw new IdentityNotFoundException($id);
        }

        return $identity;
    }

    public function save(Identity $group): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($group);
        $entityManager->flush();
    }
}