<?php


namespace Discutea\UserBundle\Mailer;

use Discutea\UserBundle\Entity\DiscuteaUserInterface;

class UserMailer
{
    /**
     * @param DiscuteaUserInterface $user
     * @param string $url
     * @return bool
     */
    public function sendResetting(DiscuteaUserInterface $user, string $url)
    {
        return true;
    }
}
