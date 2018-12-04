<?php

declare(strict_types=1);

namespace Oro\Security\User;

use Daikon\EventSourcing\Aggregate\AggregateRootInterface;
use Daikon\EventSourcing\Aggregate\AggregateRootTrait;
use Oro\Security\Entity\AuthToken;
use Oro\Security\Entity\UserProperties;
use Oro\Security\User\Activate\ActivateUser;
use Oro\Security\User\Activate\UserWasActivated;
use Oro\Security\User\Register\AuthTokenWasAdded;
use Oro\Security\User\Register\RegisterUser;
use Oro\Security\User\Register\UserWasRegistered;
use Oro\Security\User\Register\VerifyTokenWasAdded;
use Oro\Security\ValueObject\UserState;

final class User implements AggregateRootInterface
{
    use AggregateRootTrait;

    /** @var UserProperties */
    private $userProps;

    public static function register(RegisterUser $registerUser): self
    {
        return (new self($registerUser->getAggregateId()))
            ->reflectThat(UserWasRegistered::fromCommand($registerUser))
            ->reflectThat(AuthTokenWasAdded::fromCommand($registerUser))
            ->reflectThat(VerifyTokenWasAdded::fromCommand($registerUser));
    }

    public function activate(ActivateUser $activateUser): self
    {
        return $this->reflectThat(UserWasActivated::fromCommand($activateUser));
    }

    protected function whenUserWasRegistered(UserWasRegistered $userRegistered)
    {
        $this->userProps = UserProperties::fromNative($userRegistered->toNative())
            ->withValue('state', UserState::UNVERIFIED);
    }

    protected function whenAuthTokenWasAdded(AuthTokenWasAdded $tokenAdded)
    {
        $this->userProps = $this->userProps->withAuthTokenAdded(
            AuthToken::fromNative([
                'id' => $tokenAdded->getId(),
                'token' => $tokenAdded->getToken(),
                'expiresAt' => $tokenAdded->getExpiresAt()
            ])
        );
    }

    protected function whenVerifyTokenWasAdded(VerifyTokenWasAdded $tokenAdded)
    {
        $this->userProps = $this->userProps->withVerifyTokenAdded(
            AuthToken::fromNative([
                'id' => $tokenAdded->getId(),
                'token' => $tokenAdded->getToken()
            ])
        );
    }

    protected function whenUserWasActivated(UserWasActivated $userActivated)
    {
        $this->userProps = $this->userProps->withValue('state', UserState::ACTIVATED);
    }
}
