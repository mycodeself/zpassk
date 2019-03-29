<?php

namespace App\Doctrine\Repository;

use App\Entity\Password;
use App\Exception\PasswordNotFoundException;
use App\Repository\PasswordRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class PasswordRepository extends EntityRepository implements PasswordRepositoryInterface
{

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function findById(int $id): ?Password
    {
        /** @var Password|null $identity */
        $identity = $this->find($id);

        return $identity;
    }

    public function getById(int $id): Password
    {
        $identity = $this->findById($id);

        if(empty($identity)) {
            throw new PasswordNotFoundException($id);
        }

        return $identity;
    }

    public function save(Password $group): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($group);
        $entityManager->flush();
    }
}