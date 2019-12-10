<?php


namespace Discutea\UserBundle\Entity;


interface DiscuteaUserInterface
{
    public function getPlainPassword(): ?string;
}
