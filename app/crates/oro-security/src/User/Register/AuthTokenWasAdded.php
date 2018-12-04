<?php

declare(strict_types=1);

namespace Oro\Security\User\Register;

use Assert\Assertion;
use Daikon\Entity\ValueObject\Uuid;
use Daikon\EventSourcing\Aggregate\Event\DomainEvent;
use Oro\Security\ValueObject\RandomToken;

final class AuthTokenWasAdded extends DomainEvent
{
    private $id;

    private $token;

    private $expiresAt;

    public static function viaCommand(RegisterUser $registerUser): self
    {
        Assertion::isInstanceOf($registerUser, RegisterUser::class);

        return new self(
            $registerUser->getAggregateId(),
            Uuid::generate(),
            RandomToken::generate(),
            $registerUser->getAuthTokenExpiresAt()
        );
    }

    /** @param array $payload */
    public static function fromNative($payload): MessageInterface
    {
        return new self(
            AggregateId::fromNative($payload['aggregateId']),
            Uuid::fromNative($payload['id']),
            RandomToken::fromNative($payload['token']),
            Timestamp::fromNative($payload['expiresAt']),
            AggregateRevision::fromNative($payload['aggregateRevision'])
        );
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

    public function getExpiresAt(): Timestamp
    {
        return $this->expiresAt;
    }

    public function toNative(): array
    {
        return array_merge([
            'id' => $this->id->toNative(),
            'token' => $this->token->toNative(),
            'expiresAt' => $this->expiresAt->toNative()
        ], parent::toNative());
    }

    protected function __construct(
        AggregateId $aggregateId,
        Uuid $id,
        RandomToken $token,
        Timestamp $expiresAt,
        AggregateRevision $aggregateRevision = null
    ) {
        parent::__construct($aggregateId, $aggregateRevision);
        $this->id = $id;
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }
}
