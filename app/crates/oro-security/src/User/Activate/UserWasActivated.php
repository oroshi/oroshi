<?php

declare(strict_types=1);

namespace Oro\Security\User\Activate;

use Daikon\EventSourcing\Aggregate\Event\DomainEvent;
use Daikon\EventSourcing\Aggregate\Event\DomainEventInterface;
use Daikon\Interop\FromToNativeTrait;

/**
 * @map(aggregateId, Daikon\EventSourcing\Aggregate\AggregateId::fromNative)
 * @map(aggregateRevision, Daikon\EventSourcing\Aggregate\AggregateRevision::fromNative)
 */
final class UserWasActivated extends DomainEvent
{
    use FromToNativeTrait;

    public static function fromCommand(ActivateUser $activateUser): self
    {
        return self::fromNative($activateUser->toNative());
    }

    public function conflictsWith(DomainEventInterface $otherEvent): bool
    {
        return false;
    }
}
