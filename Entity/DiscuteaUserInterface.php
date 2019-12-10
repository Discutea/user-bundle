<?php


namespace Discutea\UserBundle\Entity;


interface DiscuteaUserInterface
{
    public function getId(): ?int;
    public function getPlainPassword(): ?string;
}
