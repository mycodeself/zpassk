<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\InvalidRoleException;
use App\Exception\UserAlreadyExistsException;
use App\Form\Type\UpdateUserType;
use App\Form\Type\UserType;
use App\Repository\UserRepositoryInterface;
use App\Security\AuthUser;
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
        $this->denyAccessUnlessGranted(User::ROLE_ADMIN);
        /** @var AuthUser $authUser */
        $authUser = $this->getUser();
        $user = $authUser->getUser();
        /** @var User[] $users */
        $users = $userRepository->findAllExcept($user);

        return $this->render('user/user_list.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route(path="/users/create", name="user_create")
     *
     * @param Request $request
     * @param UserService $userService
     * @return Response
     */
    public function create(Request $request, UserService $userService): Response
    {
        $this->denyAccessUnlessGranted(User::ROLE_ADMIN);
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $userService->create($data);
                $this->addFlash('success', 'The user has been created.');
            } catch (InvalidRoleException $e) {
            } catch (UserAlreadyExistsException $e) {
            }

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/user_create.html.twig', [
            'form' => $form->createView()
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
        $this->denyAccessUnlessGranted(User::ROLE_ADMIN);
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