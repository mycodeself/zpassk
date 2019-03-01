<?php

namespace App\Controller;

use App\Service\GroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route(path="/", name="index")
     *
     * @return Response
     */
    public function index(GroupService $groupService): Response
    {
        $groups = $groupService->getGroupsOfCurrentUser();

        return $this->render('index.html.twig', [
            'groups' => $groups
        ]);
    }

}