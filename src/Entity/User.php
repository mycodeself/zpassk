<?php

namespace App\Entity;

use App\Entity\ValueObject\KeyPair;
use App\Entity\ValueObject\PasswordEncoded;
use App\Exception\InvalidRoleException;
use DateTimeInterface;

class User
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    const VALID_ROLES = [self::ROLE_ADMIN, self::ROLE_USER];

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var PasswordEncoded
     */
    private $password;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var DateTimeInterface|null
     */
    private $passwordRequestedAt;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var KeyPair
     */
    private $keyPair;

    /**
     * User constructor.
     * @param string $username
     * @param string $email
     * @param PasswordEncoded $password
     * @param array $roles
     * @throws InvalidRoleException
     */
    public function __construct(string $username, string $email, PasswordEncoded $password, array $roles, KeyPair $keyPair)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->enabled = true;
        $this->setRoles($roles);
        $this->keyPair = $keyPair;
    }

    /**
     * @param array $roles
     * @throws InvalidRoleException
     */
    private function setRoles(array $roles): void
    {
        foreach ($roles as $role) {
            if(!in_array($role, self::VALID_ROLES)) {
                throw new InvalidRoleException($role);
            }
        }

        $this->roles = $roles;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return PasswordEncoded
     */
    public function getPassword(): PasswordEncoded
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPasswordRequestedAt(): ?DateTimeInterface
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     *
     */
    public function disable(): void
    {
        $this->enabled = false;
    }

    /**
     *
     */
    public function enable(): void
    {
        $this->enabled = true;
    }

    /**
     * @return KeyPair
     */
    public function keyPair(): KeyPair
    {
        return $this->keyPair;
    }

    /**
     * @param string $plainPassword
     */
    public function changePassword(string $plainPassword): void
    {
        $this->password = new PasswordEncoded($plainPassword);
        $this->token = null;
    }

    /**
     * @param string $email
     */
    public function changeEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @throws \Exception
     */
    public function requestNewPassword(): void
    {
        $this->token = bin2hex(random_bytes(32));
        $this->passwordRequestedAt = new \DateTimeImmutable();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getUsername();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function equals(User $user): bool
    {
        return $this->getId() === $user->getId();
    }

}