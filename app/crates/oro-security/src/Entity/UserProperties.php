<?php

declare(strict_types=1);

namespace Oro\Security\Entity;

use Daikon\Entity\Entity\Attribute;
use Daikon\Entity\Entity\AttributeMap;
use Daikon\Entity\Entity\Entity;
use Daikon\Entity\ValueObject\Email;
use Daikon\Entity\ValueObject\IntValue;
use Daikon\Entity\ValueObject\Text;
use Daikon\Entity\ValueObject\ValueObjectInterface;
use Daikon\EventSourcing\Aggregate\AggregateId;
use Oro\Security\ValueObject\PasswordHash;
use Oro\Security\ValueObject\UserRole;
use Oro\Security\ValueObject\UserState;
use Oro\Security\ValueObject\UserTokenList;

final class UserProperties extends Entity
{
    public static function getAttributeMap(): AttributeMap
    {
        return new AttributeMap([
            Attribute::define('aggregateId', Text::class),
            Attribute::define('aggregateRevision', IntValue::class),
            Attribute::define('username', Text::class),
            Attribute::define('email', Email::class),
            Attribute::define('role', UserRole::class),
            Attribute::define('locale', Text::class),
            Attribute::define('passwordHash', PasswordHash::class),
            Attribute::define('state', UserState::class),
            Attribute::define('tokens', UserTokenList::class)
        ]);
    }

    public function getIdentity(): ValueObjectInterface
    {
        return $this->getAggregateId();
    }

    public function getAggregateId(): Text
    {
        return $this->get('aggregateId');
    }

    public function getAggregateRevision(): IntValue
    {
        return $this->get('aggregateRevision');
    }

    public function getUsername(): Text
    {
        return $this->get('username');
    }

    public function getEmail(): Email
    {
        return $this->get('email');
    }

    public function getRole(): UserRole
    {
        return $this->get('role');
    }

    public function getLocale(): Text
    {
        return $this->get('locale');
    }

    public function getPasswordHash(): PasswordHash
    {
        return $this->get('passwordHash');
    }

    public function getState(): UserState
    {
        return $this->get('state');
    }

    public function getTokens(): UserTokenList
    {
        return $this->get('tokens');
    }
}
