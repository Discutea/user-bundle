<?php

namespace Discutea\UserBundle\Mailer;

use Discutea\UserBundle\Entity\DiscuteaUserInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class UserMailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var array
     */
    private $discuteaUserConfig;

    /**
     * UserMailer constructor.
     * @param MailerInterface $mailer
     * @param Environment $twig
     * @param array $discuteaUserConfig
     */
    public function __construct(MailerInterface $mailer, Environment $twig, array $discuteaUserConfig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->discuteaUserConfig = $discuteaUserConfig;
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
        $html = $this->twig->render("@DiscuteaUser/email/resetting.html.twig", ['user' => $user, 'confirmationUrl' => $url]);

        $email = (new Email())
            ->from($this->discuteaUserConfig['from_email_address'])
            ->to($user->getEmail())
            ->subject('Réinitialisation de votre mot de passe')
            ->html($html);

        $this->mailer->send($email);
    }
}
