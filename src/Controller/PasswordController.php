<?php

namespace App\Controller;

use App\Exception\PasswordKeyNotFoundException;
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
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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

        $users = $userRepository->findAllExcept($user);
        $passwordKey = $passwordKeyRepository->getByOwnerAndPasswordId($user, $id);
        $password = $passwordKey->getPassword();

        if(!$password->getOwner()->equals($user)) {
            throw new AccessDeniedHttpException();
        }

        return $this->render('password/password_share.html.twig', [
            'users' => $users,
            'password' => $password,
            'passwordKey' => $passwordKey
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

        return new JsonResponse();
    }
}