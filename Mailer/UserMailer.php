<?php

namespace Discutea\UserBundle\Mailer;

use Discutea\UserBundle\Entity\DiscuteaUserInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class UserMailer
{
    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param DiscuteaUserInterface $user
     * @param string $url
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendResetting(DiscuteaUserInterface $user, string $url): void
    {
        $html = $this->twig->render("email/user/resetting.mjml.twig", ['user' => $user, 'confirmationUrl' => $url]);

        $email = (new Email())
            ->from('test@discutea.com')
            ->to($user->getEmail())
            ->subject('Réinitialisation de votre mot de passe')
            ->html($html);

        $this->mailer->send($email);
    }
}
