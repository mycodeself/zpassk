<?php

namespace App\Service;

use App\Entity\Identity;
use App\Repository\GroupRepositoryInterface;
use App\Repository\IdentityRepositoryInterface;
use App\Security\LoggedInUserProvider;
use App\Service\DTO\IdentityDTO;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class IdentityService
{
    /**
     * @var IdentityRepositoryInterface
     */
    private $identityRepository;

    /**
     * @var GroupRepositoryInterface
     */
    private $groupRepository;

    /**
     * @var LoggedInUserProvider
     */
    private $loggedInUserProvider;

    /**
     * IdentityService constructor.
     * @param IdentityRepositoryInterface $identityRepository
     * @param GroupRepositoryInterface $groupRepository
     * @param LoggedInUserProvider $loggedInUserProvider
     */
    public function __construct(
        IdentityRepositoryInterface $identityRepository,
        GroupRepositoryInterface $groupRepository,
        LoggedInUserProvider $loggedInUserProvider
    )
    {
        $this->identityRepository = $identityRepository;
        $this->groupRepository = $groupRepository;
        $this->loggedInUserProvider = $loggedInUserProvider;
    }

    public function create(IdentityDTO $identityDTO): void
    {
        $group = $this->groupRepository->getById($identityDTO->getGroupId());

        if(!$this->loggedInUserHasPermissionInGroupId($identityDTO->getGroupId())) {
            throw new AccessDeniedException('Only the owner of the group can create identities on it.');
        }

        $identity = new Identity(
            $identityDTO->getUsername(),
            $identityDTO->getPassword(),
            $group
        );

        // TODO: Do encrypt things with password

        $this->identityRepository->save($identity);
    }

    public function loggedInUserHasPermissionInGroupId(int $id): bool
    {
        $user = $this->loggedInUserProvider->getUser();
        $group = $this->groupRepository->getById($id);

        if($group->getOwner()->equals($user)) {
            return true;
        }

        return false;
    }

}