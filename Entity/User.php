<?php

namespace Discutea\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @package Discutea\UserBundle\Entity
 *
 * @ORM\MappedSuperclass
 *
 */
abstract class User implements DiscuteaUserInterface, UserInterface
{
    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $email;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $enabled = false;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=180, unique=true, nullable=true)
     */
    protected $confirmationToken;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", unique=true)
     */
    protected $passwordRequestedAt;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string|null
     */
    protected $plainPassword;

    /**
     * @see DiscuteaUserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function setUsername(string $username): DiscuteaUserInterface
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function setEmail(string $email): DiscuteaUserInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function isEnabled() : bool
    {
        return $this->enabled;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function setEnabled(bool $enabled) : DiscuteaUserInterface
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function setSalt(?string $salt): DiscuteaUserInterface
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function setPassword(string $password): DiscuteaUserInterface
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function setLastLogin(?\DateTime $lastLogin): DiscuteaUserInterface
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function setConfirmationToken(?string $confirmationToken): DiscuteaUserInterface
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function getPasswordRequestedAt(): ?\DateTime
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function setPasswordRequestedAt(?\DateTime $passwordRequestedAt): DiscuteaUserInterface
    {
        $this->passwordRequestedAt = $passwordRequestedAt;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function setRoles(array $roles): DiscuteaUserInterface
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function setPlainPassword(?string $plainPassword): DiscuteaUserInterface
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @see DiscuteaUserInterface
     */
    public function isPasswordRequestNonExpired(int $ttl): bool
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
            $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }
}
