<?php


namespace Discutea\UserBundle\Entity;


interface DiscuteaUserInterface
{
    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @param string $username
     * @return DiscuteaUserInterface
     */
    public function setUsername(string $username): DiscuteaUserInterface;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @param string $email
     * @return DiscuteaUserInterface
     */
    public function setEmail(string $email): DiscuteaUserInterface;

    /**
     * @return bool
     */
    public function isEnabled() : bool;

    /**
     * @param bool $enabled
     * @return DiscuteaUserInterface
     */
    public function setEnabled(bool $enabled) : DiscuteaUserInterface;

    /**
     * @param string|null $salt
     * @return DiscuteaUserInterface
     */
    public function setSalt(?string $salt): DiscuteaUserInterface;

    /**
     * @param string $password
     * @return DiscuteaUserInterface
     */
    public function setPassword(string $password): DiscuteaUserInterface;

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime;

    /**
     * @param \DateTime|null $lastLogin
     * @return DiscuteaUserInterface
     */
    public function setLastLogin(?\DateTime $lastLogin): DiscuteaUserInterface;

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string;

    /**
     * @param string|null $confirmationToken
     * @return DiscuteaUserInterface
     */
    public function setConfirmationToken(?string $confirmationToken): DiscuteaUserInterface;

    /**
     * @return \DateTime|null
     */
    public function getPasswordRequestedAt(): ?\DateTime;

    /**
     * @param \DateTime|null $passwordRequestedAt
     * @return DiscuteaUserInterface
     */
    public function setPasswordRequestedAt(?\DateTime $passwordRequestedAt): DiscuteaUserInterface;

    /**
     * @param array $roles
     * @return DiscuteaUserInterface
     */
    public function setRoles(array $roles): DiscuteaUserInterface;

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string;

    /**
     * @param string|null $plainPassword
     * @return DiscuteaUserInterface
     */
    public function setPlainPassword(?string $plainPassword): DiscuteaUserInterface;

    /**
     * @param int $ttl
     * @return bool
     */
    public function isPasswordRequestNonExpired(int $ttl): bool;
}
