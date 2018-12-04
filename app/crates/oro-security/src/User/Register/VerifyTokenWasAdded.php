<?php

declare(strict_types=1);

namespace Oro\Security\User\Register;

use Assert\Assertion;
use Daikon\Entity\ValueObject\Uuid;
use Daikon\EventSourcing\Aggregate\Event\DomainEvent;
use Oro\Security\ValueObject\RandomToken;

/**
 * @map(aggregateId, Daikon\EventSourcing\Aggregate\AggregateId::fromNative)
 * @map(id, Daikon\Entity\ValueObject\Uuid::fromNative)
 * @map(token, Oro\Security\ValueObject\RandomToken::fromNative)
 */
final class VerifyTokenWasAdded extends DomainEvent
{
    /** @var Uuid */
    private $id;

    /** @var RandomToken */
    private $token;

    public static function fromCommand(RegisterUser $registerUser): self
    {
        return self::fromNative([
            (string)$registerUser->getAggregateId(),
            (string)Uuid::generate(),
            (string)RandomToken::generate(),
        ]);
    }

    public function conflictsWith(DomainEventInterface $otherEvent): bool
    {
        return false;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getToken(): RandomToken
    {
        return $this->token;
    }
}
