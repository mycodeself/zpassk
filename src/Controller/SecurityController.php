<?php

namespace App\Controller;

use App\Exception\InvalidRoleException;
use App\Exception\UserAlreadyExistsException;
use App\Exception\UserNotFoundException;
use App\Form\Type\ChangePasswordType;
use App\Form\Type\RecoveryPasswordType;
use App\Form\Type\UserType;
use App\Repository\UserRepositoryInterface;
use App\Service\DTO\ChangePasswordWithTokenDTO;
use App\Service\DTO\UserDTO;
use App\Service\SecurityService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login/register", name="register")
     *
     * @param Request $request
     * @param SecurityService $securityService
     * @return Response
     */
    public function register(Request $request, SecurityService $securityService): Response
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var UserDTO $userDto */
            $userDto = $form->getData();
            try {
                $securityService->registerUser($userDto);
                $message = sprintf('Hello %s, an email has been sent to %s, you need to activate your account',
                    $userDto->getUsername(),
                    $userDto->getEmail()
                );
                $this->addFlash('success', $message);
                return $this->redirectToRoute('login');
            } catch (InvalidRoleException $e) {
                $this->addFlash('error', 'An error has occurred, try again later');
            } catch (UserAlreadyExistsException $e) {
                $this->addFlash('error', 'Username is not available');
            }
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

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

    /**
     * @Route("/login/activate-account/{token}", name="activate_account_token")
     *
     * @param string $token
     * @param SecurityService $securityService
     * @return Response
     */
    public function activateAccountWithToken(string $token, SecurityService $securityService): Response
    {
        try {
            $securityService->activateAccount($token);
        } catch (UserNotFoundException $e) {
            return $this->redirectToRoute('index');
        }

        $this->addFlash('success', 'Your account has been enabled. Sign in!');
        return $this->redirectToRoute('index');
    }
}