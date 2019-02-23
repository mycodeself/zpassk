<?php

namespace App\Controller;

use App\Exception\UserNotFoundException;
use App\Form\Type\ChangePasswordType;
use App\Form\Type\RecoveryPasswordType;
use App\Repository\UserRepositoryInterface;
use App\Service\DTO\ChangePasswordWithTokenDTO;
use App\Service\SecurityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="login")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {

    }

    /**
     * @Route("/login/recovery-password", name="recovery_password")
     *
     * @param Request $request
     * @return Response
     */
    public function recoveryPassword(Request $request, SecurityService $securityService): Response
    {
        $form = $this->createForm(RecoveryPasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $securityService->recoveryPassword($data);
            $this->addFlash(
                'success',
                'We have sent an email with your username and instructions to reset your password.'
            );
            return $this->redirectToRoute('login');
        }

        return $this->render('security/recovery_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login/recovery-password/{token}", name="change_password_token")
     *
     * @param Request $request
     * @return Response
     */
    public function changePasswordWithToken(
        Request $request,
        string $token,
        UserRepositoryInterface $userRepository,
        SecurityService $securityService): Response
    {
        $user = $userRepository->findByToken($token);

        if(empty($user)) {
            return $this->redirectToRoute('login');
        }

        $changePasswordDTO = new ChangePasswordWithTokenDTO($token, '');
        $form = $this->createForm(ChangePasswordType::class, $changePasswordDTO);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $changePasswordDTO = $form->getData();
            try {
                $securityService->changePasswordWithToken($changePasswordDTO);
            } catch (UserNotFoundException $e) {
            }
            $this->addFlash(
                'success',
                'Your password has been changed. Log in!'
            );
            return $this->redirectToRoute('login');
        }

        return $this->render('security/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}