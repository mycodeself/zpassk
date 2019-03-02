<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\Type\GroupType;
use App\Repository\GroupRepositoryInterface;
use App\Security\AuthUser;
use App\Service\DTO\GroupDTO;
use App\Service\GroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{

    /**
     * @Route(path="/groups/create", name="group_create")
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request, GroupService $groupService): Response
    {
        /** @var AuthUser $authUser */
        $authUser = $this->getUser();
        $user = $authUser->getUser();
        $groupDTO = new GroupDTO('', $user->getId());
        $form = $this->createForm(GroupType::class, $groupDTO);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $groupDTO = $form->getData();
            $groupService->create($groupDTO);
            $this->addFlash('success',sprintf('The group "%s" has been created.', $groupDTO->getName()));
            return new RedirectResponse($this->generateUrl('index'));
        }

        return $this->render('group/group_create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/groups/{id}", name="group_update")
     *
     * @param Request $request
     * @param int $id
     * @param GroupService $groupService
     * @param GroupRepositoryInterface $groupRepository
     * @return Response
     */
    public function update(
        Request $request,
        int $id,
        GroupService $groupService,
        GroupRepositoryInterface $groupRepository): Response
    {
        $group = $groupRepository->getById($id);
        $form = $this->createForm(GroupType::class, GroupDTO::fromGroup($group));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var GroupDTO $groupDTO */
            $groupDTO = $form->getData();
            $groupService->update($groupDTO);
            $this->addFlash('success',sprintf('The group "%s" has been updated.', $groupDTO->getName()));
            return new RedirectResponse($this->generateUrl('index'));
        }

        return $this->render('group/group_create.html.twig', [
            'form' => $form->createView(),
            'group' => $group
        ]);
    }
}