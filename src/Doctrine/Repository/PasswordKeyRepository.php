<?php

namespace App\Doctrine\Repository;

use App\Entity\Password;
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
    public function findById(int $passwordId, int $userId): ?PasswordKey
    {
        /** @var PasswordKey $key */
        $key = $this->find([
            'password' => $passwordId,
            'user' => $userId
        ]);

        return $key;
    }

    /**
     * @param int $id
     * @return PasswordKey
     * @throws PasswordKeyNotFoundException
     */
    public function getById(int $passwordId, int $userId): PasswordKey
    {
        $key = $this->findById($passwordId, $userId);

        if(empty($key)) {
            throw new PasswordKeyNotFoundException($passwordId, $userId);
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

    public function findAllByOwnerGroupedByPassword(User $owner): array
    {
        $qb = $this->createQueryBuilder('pk');
        $query = $qb
            ->distinct()
            ->innerJoin('pk.password', 'p')
            ->andWhere('p.owner = :owner')
            ->andWhere('pk.user = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('p.name', 'ASC')
            ->groupBy('pk.password')
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

    /**
     * @param User $owner
     * @param int $passwordId
     * @return PasswordKey
     * @throws PasswordKeyNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByOwnerAndPasswordId(User $owner, int $passwordId): PasswordKey
    {
        $qb = $this->createQueryBuilder('pk');
        $query = $qb
            ->innerJoin('pk.password', 'p')
            ->andWhere('p.owner = :owner')
            ->andWhere('p.id = :passwordId')
            ->setParameters([
                'owner' => $owner,
                'passwordId' => $passwordId
            ])
            ->groupBy('pk.password')
            ->getQuery();

        $result = $query->getOneOrNullResult();

        if(empty($result)) {
            throw new PasswordKeyNotFoundException($passwordId, $owner->getId());
        }

        return $result;
    }

    /**
     * @param int $id
     * @param User $user
     * @return array
     */
    public function findByPasswordIdExcludeUser(int $id, User $user): array
    {
        $qb = $this->createQueryBuilder('pk');
        $query = $qb
            ->innerJoin('pk.password', 'p')
            ->innerJoin('pk.user', 'u')
            ->andWhere('p.id = :passwordId')
            ->andWhere('u.id != :userId')
            ->setParameters([
                'passwordId' => $id,
                'userId' => $user->getId()
            ])
            ->orderBy('u.username', 'ASC')
            ->groupBy('pk.user')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param PasswordKey $key
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(PasswordKey $key): void
    {
        $em = $this->getEntityManager();
        $em->remove($key);
        $em->flush();
    }
}