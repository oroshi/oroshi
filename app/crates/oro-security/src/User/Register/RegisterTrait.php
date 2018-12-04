<?php

declare(strict_types=1);

namespace Oro\Security\User\Register;

use Daikon\Entity\ValueObject\Email;
use Daikon\Entity\ValueObject\Text;
use Daikon\Entity\ValueObject\Timestamp;
use Oro\Security\ValueObject\PasswordHash;
use Oro\Security\ValueObject\UserRole;

/**
 * @map(username, Daikon\Entity\ValueObject\Text::fromNative)
 * @map(email, Daikon\Entity\ValueObject\Email::fromNative)
 * @map(role, Oro\Security\ValueObject\UserRole::fromNative)
 * @map(locale, Daikon\Entity\ValueObject\Text::fromNative)
 * @map(passwordHash, Oro\Security\ValueObject\PasswordHash::fromNative)
 * @map(authTokenExpiresAt, Daikon\Entity\ValueObject\Timestamp::fromNative)
 */
trait RegisterTrait
{
    /** @var Text */
    private $username;

    /** @var Email */
    private $email;

    /** @var UserRole */
    private $role;

    /** @var Text */
    private $locale;

    /** @var PasswordHash */
    private $passwordHash;

    /** @var Timestamp */
    private $authTokenExpiresAt;

    public function getUsername(): Text
    {
        return $this->username;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function getLocale(): Text
    {
        return $this->locale;
    }

    public function getPasswordHash(): PasswordHash
    {
        return $this->passwordHash;
    }

    public function getAuthTokenExpiresAt(): Timestamp
    {
        return $this->authTokenExpiresAt;
    }
}
