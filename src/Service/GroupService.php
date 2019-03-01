<?php

namespace App\Service;

use App\Entity\Group;
use App\Repository\GroupRepositoryInterface;
use App\Security\LoggedInUserProvider;
use App\Service\DTO\GroupDTO;

class GroupService
{
    /**
     * @var GroupRepositoryInterface
     */
    private $groupRepository;

    /**
     * @var LoggedInUserProvider
     */
    private $loggedInUserProvider;

    /**
     * GroupService constructor.
     * @param GroupRepositoryInterface $groupRepository
     * @param LoggedInUserProvider $loggedInUserProvider
     */
    public function __construct(
        GroupRepositoryInterface $groupRepository,
        LoggedInUserProvider $loggedInUserProvider
    )
    {
        $this->groupRepository = $groupRepository;
        $this->loggedInUserProvider = $loggedInUserProvider;
    }

    /**
     * @param GroupDTO $groupDTO
     */
    public function create(GroupDTO $groupDTO): void
    {
        $group = new Group(
            $groupDTO->getName(),
            $this->loggedInUserProvider->getUser()
        );

        $this->groupRepository->save($group);
    }

    /**
     * @return array
     */
    public function getGroupsOfCurrentUser(): array
    {
        $user = $this->loggedInUserProvider->getUser();

        return $this->groupRepository->findAllByOwner($user);
    }
}