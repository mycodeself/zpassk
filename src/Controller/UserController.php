<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route(path="/users", name="user_list")
     *
     * @param Request $request
     * @return Response
     */
    public function listAll(Request $request, UserRepositoryInterface $userRepository): Response
    {
        /** @var User[] $users */
        $users = $userRepository->findAll();

        return $this->render('user/user_list.html.twig', [
            'users' => $users
        ]);
    }

}