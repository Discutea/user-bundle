<?php

namespace Discutea\UserBundle\EventListener;

use Discutea\UserBundle\Entity\DiscuteaUserInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PasswordListener
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof DiscuteaUserInterface) {
            $this->updatePassword($entity);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Exception
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof DiscuteaUserInterface) {
            $this->updatePassword($entity);
        }
    }

    /**
     * {@inheritdoc}
     */
    private function updatePassword(DiscuteaUserInterface $user): void
    {
        if (!$user instanceof UserInterface) {
            throw new \LogicException('This entity is not a valid user.');
        }

        if ($this->passwordEncoder->isPasswordValid($user, $user->getPlainPassword())) {
            $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        }
    }
}
