<?php

namespace App\Controller;

use App\Exception\PasswordKeyNotFoundException;
use App\Exception\PasswordNotFoundException;
use App\Exception\UserNotFoundException;
use App\Form\Type\PasswordFormType;
use App\Repository\PasswordKeyRepositoryInterface;
use App\Repository\PasswordRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Security\AuthUser;
use App\Service\DTO\PasswordDTO;
use App\Service\PasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{

    /**
     * @Route(path="/passwords/create", name="password_create")
     *
     * @param Request $request
     * @param PasswordService $passwordService
     * @return Response
     */
    public function create(Request $request, PasswordService $passwordService): Response
    {
        $passwordDTO = new PasswordDTO();
        $form = $this->createForm(PasswordFormType::class, $passwordDTO);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $passwordService->create($data);
            $this->addFlash('success', 'The password has been saved');
            return new RedirectResponse($this->generateUrl('index'));
        }

        return $this->render('password/password_create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/passwords/{id}/share", name="password_share")
     *
     * @param Request $request
     * @param PasswordService $passwordService
     * @return Response
     */
    public function share(
        int $id,
        UserRepositoryInterface $userRepository,
        PasswordKeyRepositoryInterface $passwordKeyRepository
    ): Response
    {
        /** @var AuthUser $authUser */
        $authUser = $this->getUser();
        $user = $authUser->getUser();

        $passwordKey = $passwordKeyRepository->getByOwnerAndPasswordId($user, $id);
        $password = $passwordKey->getPassword();

        if(!$password->getOwner()->equals($user)) {
            throw new AccessDeniedHttpException();
        }

        $users = $userRepository->findAllExcept($user);

        $shared = $passwordKeyRepository->findByPasswordIdExcludeUser($id, $user);

        return $this->render('password/password_share.html.twig', [
            'users' => $users,
            'password' => $password,
            'passwordKey' => $passwordKey,
            'shared' => $shared
        ]);
    }

    /**
     * @Route(path="/ajax/passwords/{id}/share", name="ajax_password_share")
     *
     * @param Request $request
     * @param PasswordService $passwordService
     * @return Response
     */
    public function shareAjax(Request $request, int $id, PasswordService $passwordService): JsonResponse
    {
        if(!$request->isXmlHttpRequest() || $request->getMethod() !== Request::METHOD_POST) {
            throw new NotFoundHttpException();
        }

        if(!$request->request->has('key') || !$request->request->has('userId')) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $key = $request->request->get('key');
        $userId = $request->request->get('userId');

        try {
            $passwordService->share($key, $userId, $id);
        } catch (PasswordKeyNotFoundException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (UserNotFoundException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        $this->addFlash('success', 'The password has been shared.');

        return new JsonResponse();
    }

    /**
     * @Route(path="/passwords/{passwordId}/unshare/{userId}", name="password_unshare")
     *
     * @param int $passwordId
     * @param int $userId
     * @param PasswordRepositoryInterface $passwordRepository
     * @return Response
     */
    public function unshare(
        int $passwordId,
        int $userId,
        PasswordRepositoryInterface $passwordRepository,
        PasswordKeyRepositoryInterface $passwordKeyRepository
    ): Response
    {
        try {
            $password = $passwordRepository->getById($passwordId);
        } catch (PasswordNotFoundException $e) {
            throw new NotFoundHttpException();
        }

        /** @var AuthUser $authUser */
        $authUser = $this->getUser();
        $user = $authUser->getUser();

        if(!$password->getOwner()->equals($user)) {
            throw new AccessDeniedHttpException();
        }

        $passwordKey = $passwordKeyRepository->findById($passwordId, $userId);
        $passwordKeyRepository->delete($passwordKey);

        $this->addFlash('success', sprintf('The password "%s" has been unshared with user.', $password));

        return new RedirectResponse($this->generateUrl('password_share', ['id' => $passwordId]));
    }

    /**
     * @Route(path="/passwords/{id}/delete", name="password_delete")

     *
     * @param int $id
     * @return Response
     */
    public function delete(int $id, PasswordRepositoryInterface $passwordRepository): Response
    {
        /** @var AuthUser $authUser */
        $authUser = $this->getUser();
        $user = $authUser->getUser();

        try {
            $password = $passwordRepository->getById($id);
        } catch (PasswordNotFoundException $e) {
            throw new NotFoundHttpException();
        }

        if(!$password->getOwner()->equals($user)) {
            throw new AccessDeniedHttpException();
        }

        $passwordRepository->delete($password);

        $this->addFlash('success', sprintf('The password "%s" has been removed.', $password));

        return new RedirectResponse($this->generateUrl('index'));
    }
}