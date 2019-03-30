<?php

namespace App\Doctrine\Repository;

use App\Entity\PasswordKey;
use App\Entity\User;
use App\Exception\PasswordKeyNotFoundException;
use App\Repository\PasswordKeyRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class PasswordKeyRepository extends EntityRepository implements PasswordKeyRepositoryInterface
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
     * @return PasswordKey|null
     */
    public function findById(int $id): ?PasswordKey
    {
        /** @var PasswordKey $key */
        $key = $this->find($id);

        return $key;
    }

    /**
     * @param int $id
     * @return PasswordKey
     * @throws PasswordKeyNotFoundException
     */
    public function getById(int $id): PasswordKey
    {
        $key = $this->findById($id);

        if(empty($key)) {
            throw new PasswordKeyNotFoundException($id);
        }

        return $key;
    }

    /**
     * @param PasswordKey $key
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(PasswordKey $key): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($key);
        $entityManager->flush();
    }

    public function findAllByOwner(User $owner): array
    {
        $qb = $this->createQueryBuilder('pk');
        $query = $qb
            ->innerJoin('pk.password', 'p')
            ->andWhere('p.owner = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('p.name', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    public function findSharedForUser(User $user): array
    {
        $qb = $this->createQueryBuilder('pk');
        $query = $qb
            ->innerJoin('pk.password', 'p')
            ->andWhere('pk.user = :user')
            ->andWhere('p.owner != :user')
            ->setParameter('user', $user)
            ->orderBy('p.name', 'ASC')
            ->getQuery();

        return $query->getResult();
    }
}