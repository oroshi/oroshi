<?php

declare(strict_types=1);

namespace Oro\Security\User;

use Daikon\EventSourcing\Aggregate\AggregateRootInterface;
use Daikon\EventSourcing\Aggregate\AggregateRootTrait;
use Oro\Security\Entity\UserProperties;
use Oro\Security\User\Activate\ActivateUser;
use Oro\Security\User\Activate\UserWasActivated;
use Oro\Security\User\Register\RegisterUser;
use Oro\Security\User\Register\UserWasRegistered;
use Oro\Security\ValueObject\UserState;

final class User implements AggregateRootInterface
{
    use AggregateRootTrait;

    /** @var UserProperties */
    private $userProps;

    public static function register(RegisterUser $registerUser): self
    {
        return (new self($registerUser->getAggregateId()))
            ->reflectThat(UserWasRegistered::fromCommand($registerUser));
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

    protected function whenUserWasActivated(UserWasActivated $userActivated)
    {
        $this->userProps = $this->userProps->withValue('state', UserState::ACTIVATED);
    }
}
