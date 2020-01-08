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
     * @Route("/send-email", name="discutea_user_registration_send_email", methods={"GET"})
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
        Request $request,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $router,
        array $discuteaUserConfig
    ) : Response {
        $username = $request->request->get('username');

        $user = $entityManager->getRepository($discuteaUserConfig['user_class'])->findOneBy(array('username' => $username));

        if ($user instanceof DiscuteaUserInterface && !$user->isEnabled()) {
            $user->setPasswordRequestedAt(new \DateTime());

            $mailer->sendConfirmation(
                $user,
                $router->generate('discutea_user_registration_check_email', array('confirmationToken' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL)
            );

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('@DiscuteaUser/registration/confirmation.html.twig', [
            'email' => $user->getEmail()
        ]);
    }
}
