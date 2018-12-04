<?php

declare(strict_types=1);

namespace Oro\Security\User\Register;

use Daikon\EventSourcing\Aggregate\Event\DomainEvent;
use Daikon\EventSourcing\Aggregate\Event\DomainEventInterface;
use Daikon\Interop\FromToNativeTrait;
use Oro\Security\ValueObject\UserState;

/**
 * @map(aggregateId, Daikon\EventSourcing\Aggregate\AggregateId::fromNative)
 * @map(aggregateRevision, Daikon\EventSourcing\Aggregate\AggregateRevision::fromNative)
 */
final class UserWasRegistered extends DomainEvent
{
    use FromToNativeTrait;
    use RegisterTrait;

    public static function fromCommand(RegisterUser $registerUser): self
    {
        return self::fromNative($registerUser->toNative());
    }

    public function conflictsWith(DomainEventInterface $otherEvent): bool
    {
        return false;
    }
}
