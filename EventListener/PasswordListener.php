<?php

namespace Discutea\UserBundle\EventListener;

use Discutea\UserBundle\Entity\DiscuteaUserInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\SelfSaltingEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PasswordListener
{
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
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
     * @param DiscuteaUserInterface $user
     * @throws \Exception
     */
    private function updatePassword(DiscuteaUserInterface $user): void
    {
        if (0 === strlen($user->getPlainPassword())) {
            return;
        }

        if (!$user instanceof UserInterface) {
            throw new \LogicException('This entity is not a valid user.');
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        if ($encoder instanceof SelfSaltingEncoderInterface) {
            $user->setSalt(null);
        } else {
            $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            $user->setSalt($salt);
        }

        $hashedPassword = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}
