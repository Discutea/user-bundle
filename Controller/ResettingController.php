<?php

namespace Discutea\UserBundle\Controller;

use Discutea\UserBundle\Entity\DiscuteaUserInterface;
use Discutea\UserBundle\Form\ResettingType;
use Discutea\UserBundle\Mailer\UserMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * @Route("/resetting")
 */
class ResettingController extends AbstractController
{
    /**
     * @Route("/request", name="discutea_user_resetting_request")
     */
    public function request()
    {
        return $this->render('@DiscuteaUser/resetting/request.html.twig');
    }

    /**
     * @Route("/send-email", name="discutea_user_resetting_send_email", methods={"POST"})
     *
     * @param UserMailer $mailer
     * @param Request $request
     * @param TokenGeneratorInterface $tokenGenerator
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $router
     * @param array $discuteaUserConfig
     * @return RedirectResponse
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendEmail(
        UserMailer $mailer,
        Request $request,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $router,
        array $discuteaUserConfig
    ) {
        $username = $request->request->get('username');

        $user = $entityManager->getRepository($discuteaUserConfig['user_class'])->findOneBy(array('email' => $username));

        if ($user instanceof DiscuteaUserInterface && !$user->isPasswordRequestNonExpired($discuteaUserConfig['retry_ttl'])) {
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($tokenGenerator->generateToken());
            }

            $user->setPasswordRequestedAt(new \DateTime());

            $mailer->sendResetting(
                $user,
                $router->generate('discutea_user_resetting_reset', array('confirmationToken' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL)
            );

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return new RedirectResponse($this->generateUrl('discutea_user_resetting_check_email', array('username' => $username)));
    }

    /**
     * @Route("/check-email", name="discutea_user_resetting_check_email", methods={"GET"})
     *
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function checkEmail(Request $request, array $discuteaUserConfig)
    {
        $username = $request->query->get('username');

        if (empty($username)) {
            return $this->redirectToRoute('discutea_user_resetting_request');
        }

        return $this->render('@DiscuteaUser/resetting/check_email.html.twig', array(
            'tokenLifetime' => ceil($discuteaUserConfig['retry_ttl'] / 3600),
        ));
    }

    /**
     * @Route("/reset/{confirmationToken}", name="discutea_user_resetting_reset", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param array $discuteaUserConfig
     * @param string $confirmationToken
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function reset(Request $request, EntityManagerInterface $entityManager, array $discuteaUserConfig, string $confirmationToken)
    {
        $user = $entityManager->getRepository($discuteaUserConfig['user_class'])->findOneBy(array('confirmationToken' => $confirmationToken));

        if (!$user instanceof DiscuteaUserInterface) {
            throw $this->createNotFoundException('Not found');
        }

        $form = $this->createForm(ResettingType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPasswordRequestedAt(null);
            $user->setConfirmationToken(null);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('discutea_user_login');
        }

        return $this->render('@DiscuteaUser/resetting/reset.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
}
