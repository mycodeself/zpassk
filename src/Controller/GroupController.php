<?php

namespace App\Controller;

use App\Form\Type\GroupType;
use App\Security\AuthUser;
use App\Service\DTO\GroupDTO;
use App\Service\GroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        }

        return $this->render('group/group_create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}