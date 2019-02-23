<?php

namespace App\Mail;

use App\Entity\User;

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
     * MailSender constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $templating
     * @param string $defaultFrom
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, string $defaultFrom)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->defaultFrom = $defaultFrom;
    }

    /**
     * @param User $user
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendRecoveryPasswordEmail(User $user): void
    {
        $body = $this->templating->render('mail/recovery_password_mail.html.twig', [
            'user' => $user
        ]);

        $message = $this->createMessage()
            ->setSubject('Forgot your password?')
            ->setFrom($this->defaultFrom, 'SocialIM')
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