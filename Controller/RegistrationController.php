<?php

namespace Discutea\UserBundle\Controller;

use Discutea\UserBundle\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/registration")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="discutea_user_registration_register")
     */
    public function registration(Request $request, array $discuteaUserConfig): Response
    {
        $class = $discuteaUserConfig['user_class'];
        $user = new $class();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('@DiscuteaUser/registration/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
