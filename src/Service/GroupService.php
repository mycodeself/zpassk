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
     * @var GroupImageUploader
     */
    private $groupImageUploader;

    /**
     * GroupService constructor.
     * @param GroupRepositoryInterface $groupRepository
     * @param LoggedInUserProvider $loggedInUserProvider
     * @param GroupImageUploader $groupImageUploader
     */
    public function __construct(
        GroupRepositoryInterface $groupRepository,
        LoggedInUserProvider $loggedInUserProvider,
        GroupImageUploader $groupImageUploader
    )
    {
        $this->groupRepository = $groupRepository;
        $this->loggedInUserProvider = $loggedInUserProvider;
        $this->groupImageUploader = $groupImageUploader;
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

        if(!empty($groupDTO->getImage())) {
            $this->groupImageUploader->uploadImageToGroup($groupDTO->getImage(), $group);
        }

        $this->groupRepository->save($group);
    }

    /**
     * @param GroupDTO $groupDTO
     */
    public function update(GroupDTO $groupDTO): void
    {
        $group = $this->groupRepository->getById($groupDTO->getId());

        if(!empty($groupDTO->getImage())) {
            $this->groupImageUploader->uploadImageToGroup($groupDTO->getImage(), $group);
        }

        $group->setName($groupDTO->getName());

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