<?php

namespace App\Doctrine\Repository;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{

    /**
     * @return array
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @param string $username
     * @return User
     */
    public function findByUsername(string $username): ?User
    {
        /** @var User $user */
        $user = $this->findOneBy(['username' => $username]);

        return $user;
    }

    /**
     * @param int $id
     * @return User
     */
    public function findById(int $id): User
    {
        /** @var User $user */
        $user = $this->find($id);

        return $user;
    }

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function getById(int $id): User
    {
        $user = $this->findById($id);

        if(empty($user)) {
            throw new UserNotFoundException((string) $id);
        }

        return $user;
    }

    /**
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        /** @var User $user */
        $user = $this->findOneBy(['email' => $email]);

        return $user;
    }

    /**
     * @param string $token
     * @return User|null
     */
    public function findByToken(string $token): ?User
    {
        /** @var User $user */
        $user = $this->findOneBy(['token' => $token]);

        return $user;
    }

    /**
     * @param User $user
     * @return array
     */
    public function findAllExcept(User $user): array
    {
        $qb = $this->createQueryBuilder('u');

        $qb
            ->where('u.id != :id')
            ->setParameter('id', $user->getId());

        return $qb->getQuery()->getResult();
    }

    public function findAllBySharedPasswordIdExcludeUser(int $id, User $user): array
    {
        $qb = $this->createQueryBuilder('u');


    }

    /**
     * @param string $token
     * @return User|null
     */
    public function findByActivationToken(string $token): ?User
    {
        /** @var User|null $user */
        $user = $this->findOneBy(['activationToken' => $token]);

        return $user;
    }
}