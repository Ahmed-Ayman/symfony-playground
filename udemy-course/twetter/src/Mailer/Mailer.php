<?php


namespace App\Mailer;


use App\Entity\User;

class Mailer
{

    /**
     * @var \Swift_mailer
     */
    private $mailer;
    /**
     * @var \Twig\Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $mailFrom;

    public function __construct(\Swift_Mailer $mailer, \Twig\Environment $twig, string $mailFrom)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $mailFrom;
    }

    public function sendConfirmationEmail(User $user)
    {
        $message = (new \Swift_Message())
            ->setSubject('welcome to the micro post app')
            ->setFrom($this->mailFrom)
            ->setTo($user->getEmail())
            ->setBody($this->twig->render('mail/registration.html.twig', ['user' => $user]), 'text/html');

        $this->mailer->send($message);
    }
}