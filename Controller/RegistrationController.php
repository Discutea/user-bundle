<?php

namespace Discutea\UserBundle\Controller;

use Discutea\UserBundle\Entity\DiscuteaUserInterface;
use Discutea\UserBundle\Form\RegistrationType;
use Discutea\UserBundle\Mailer\UserMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * @Route("/registration")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="discutea_user_registration_register")
     */
    public function registration(Request $request, TokenGeneratorInterface $tokenGenerator, array $discuteaUserConfig): Response
    {
        $class = $discuteaUserConfig['user_class'];
        $user = new $class();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setConfirmationToken($tokenGenerator->generateToken());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return new RedirectResponse($this->generateUrl('discutea_user_registration_send_email', array('username' => $user->getUsername())));
        }

        return $this->render('@DiscuteaUser/registration/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/send-email/{username}", name="discutea_user_registration_send_email", methods={"GET"})
     *
     * @param UserMailer $mailer
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $router
     * @param array $discuteaUserConfig
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendEmail(
        UserMailer $mailer,
        string $username,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $router,
        array $discuteaUserConfig
    ) : Response {
        $user = $entityManager->getRepository($discuteaUserConfig['user_class'])->findOneBy(array('username' => $username));

        if (!$user instanceof DiscuteaUserInterface || $user->isEnabled()) {
            throw $this->createAccessDeniedException('Oops');
        }

        $user->setPasswordRequestedAt(new \DateTime());

        $mailer->sendConfirmation(
            $user,
            $router->generate('discutea_user_registration_check_email', array(
                'confirmationToken' => $user->getConfirmationToken(),
                'email'             => $user->getEmail()
            ), UrlGeneratorInterface::ABSOLUTE_URL)
        );

        return $this->render('@DiscuteaUser/registration/confirmation.html.twig', [
            'email' => $user->getEmail()
        ]);
    }

    /**
     * @Route("/check-email/{confirmationToken}/{email}", name="discutea_user_registration_check_email", methods={"GET"})
     *
     * @param EntityManagerInterface $entityManager
     * @param array $discuteaUserConfig
     * @param string $confirmationToken
     * @param string $email
     * @return Response
     */
    public function checkEmail(
        EntityManagerInterface $entityManager,
        array $discuteaUserConfig,
        string $confirmationToken,
        string $email
    ) : Response {
        $user = $entityManager->getRepository($discuteaUserConfig['user_class'])->findOneBy(array(
            'confirmationToken' => $confirmationToken,
            'email' => $email
        ));

        if (!$user instanceof DiscuteaUserInterface || $user->isEnabled()) {
            throw $this->createAccessDeniedException('Oops');
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('@DiscuteaUser/registration/confirmed.html.twig');
    }
}
