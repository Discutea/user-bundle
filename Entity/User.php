<?php

namespace Discutea\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
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
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @param string $username
     * @return string
     */
    public function setUsername(string $username): string
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return DiscuteaUserInterface
     */
    public function setEmail(string $email): DiscuteaUserInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled() : bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return DiscuteaUserInterface
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
     * @param string|null $salt
     * @return DiscuteaUserInterface
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
     * @param string $password
     * @return DiscuteaUserInterface
     */
    public function setPassword(string $password): DiscuteaUserInterface
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime|null $lastLogin
     * @return User
     */
    public function setLastLogin(?\DateTime $lastLogin): DiscuteaUserInterface
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param string|null $confirmationToken
     * @return User
     */
    public function setConfirmationToken(?string $confirmationToken): DiscuteaUserInterface
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getPasswordRequestedAt(): ?\DateTime
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param \DateTime|null $passwordRequestedAt
     * @return User
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
     * @param array $roles
     * @return DiscuteaUserInterface
     */
    public function setRoles(array $roles): DiscuteaUserInterface
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     * @return User
     */
    public function setPlainPassword(?string $plainPassword): self
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
}
