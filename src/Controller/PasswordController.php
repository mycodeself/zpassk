<?php

namespace App\Controller;

use App\Form\Type\PasswordFormType;
use App\Security\AuthUser;
use App\Service\DTO\PasswordDTO;
use App\Service\PasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
}