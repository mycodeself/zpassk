<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UpdateUserType;
use App\Repository\UserRepositoryInterface;
use App\Service\DTO\UpdateUserDTO;
use App\Service\UserService;
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

    /**
     * @Route(path="/users/{id}", name="user_update")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws \App\Exception\UserNotFoundException
     */
    public function update(
        Request $request,
        int $id,
        UserRepositoryInterface $userRepository,
        UserService $userService): Response
    {
        $user = $userRepository->getById($id);
        $updateUser = UpdateUserDTO::fromUser($user);
        $form = $this->createForm(UpdateUserType::class, $updateUser);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $updateUser = $form->getData();
            $userService->update($updateUser);

            $this->addFlash('success', 'The user has been updated!');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/user_update.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}