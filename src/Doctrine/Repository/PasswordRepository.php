<?php

namespace App\Doctrine\Repository;

use App\Entity\Password;
use App\Exception\PasswordNotFoundException;
use App\Repository\PasswordRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class PasswordRepository extends EntityRepository implements PasswordRepositoryInterface
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
     * @return Password|null
     */
    public function findById(int $id): ?Password
    {
        /** @var Password|null $identity */
        $identity = $this->find($id);

        return $identity;
    }

    /**
     * @param int $id
     * @return Password
     * @throws PasswordNotFoundException
     */
    public function getById(int $id): Password
    {
        $password = $this->findById($id);

        if(empty($password)) {
            throw new PasswordNotFoundException($id);
        }

        return $password;
    }

    /**
     * @param Password $password
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Password $password): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($password);
        $entityManager->flush();
    }
}