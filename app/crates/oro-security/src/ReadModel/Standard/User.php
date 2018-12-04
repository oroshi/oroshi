<?php

declare(strict_types=1);

namespace Oro\Security\ReadModel\Standard;

use Daikon\Entity\ValueObject\Email;
use Daikon\Entity\ValueObject\Text;
use Daikon\ReadModel\Projection\ProjectionInterface;
use Daikon\ReadModel\Projection\ProjectionTrait;
use Oro\Security\User\Activate\UserWasActivated;
use Oro\Security\User\Register\UserWasRegistered;
use Oro\Security\ValueObject\PasswordHash;
use Oro\Security\ValueObject\UserRole;
use Oro\Security\ValueObject\UserState;

final class User implements ProjectionInterface
{
    use ProjectionTrait;

    public function getUsername(): Text
    {
        return Text::fromNative($this->state['username'] ?? '');
    }

    public function getEmail(): Email
    {
        return Email::fromNative($this->state['email'] ?? '');
    }

    public function getLocale(): Text
    {
        return Text::fromNative($this->state['locale'] ?? '');
    }

    public function getRole(): UserRole
    {
        return UserRole::fromNative($this->state['role'] ?? 'user');
    }

    public function getState(): UserState
    {
        return UserState::fromNative($this->state['state'] ?? UserState::UNVERIFIED);
    }

    public function getPasswordHash(): ?PasswordHash
    {
        if (isset($this->state['passwordHash'])) {
            return PasswordHash::fromNative($this->state['passwordHash']);
        }
        return null;
    }

    private function whenUserWasRegistered(UserWasRegistered $userWasRegistered)
    {
        return self::fromNative(array_merge(
            $this->state,
            $userWasRegistered->toNative(),
            ['state' => UserState::UNVERIFIED]
        ));
    }

    private function whenUserWasActivated(UserWasActivated $userWasActivated)
    {
        return self::fromNative(array_merge(
            $this->state,
            [
                'aggregateRevision' => $userWasActivated->getAggregateRevision()->toNative(),
                'state' => UserState::ACTIVATED
            ]
        ));
    }
}
