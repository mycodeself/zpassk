<?php

namespace App\Mail;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class MailSender
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $templating;

    /**
     * @var string
     */
    private $defaultFrom;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * MailSender constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $templating
     * @param string $defaultFrom
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, string $defaultFrom, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->defaultFrom = $defaultFrom;
        $this->router = $router;
    }

    /**
     * @param User $user
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendRecoveryPasswordMail(User $user): void
    {
        $url = $this->router->generate('change_password_token', [
            'token' => $user->getToken()
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $body = $this->templating->render('mail/recovery_password_mail.html.twig', [
            'user' => $user,
            'url' => $url
        ]);

        $message = $this->createMessage()
            ->setSubject('Forgot your password?')
            ->setFrom($this->defaultFrom, 'Zero PASSword Knowledge')
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }

    public function sendActivateAccountMail(User $user): void
    {
        $url = $this->router->generate('activate_account_token', [
            'token' => $user->activationToken()
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $body = $this->templating->render('mail/activate_account_mail.html.twig', [
            'user' => $user,
            'url' => $url
        ]);

        $message = $this->createMessage()
            ->setSubject('Activate your account')
            ->setFrom($this->defaultFrom, 'Zero PASSword Knowledge')
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }

    /**
     * @return \Swift_Message
     */
    private function createMessage(): \Swift_Message
    {
        return new \Swift_Message();
    }
}