<?php

declare(strict_types=1);

namespace Oro\Security\ValueObject;

use Assert\Assertion;
use Daikon\Entity\ValueObject\ValueObjectInterface;

final class UserState implements ValueObjectInterface
{
    public const UNVERIFIED = 'unverified';

    public const ACTIVATED = 'activated';

    public const DEACTIVATED = 'deactivated';

    public const DELETED = 'deleted';

    private const STATES = [
        'unverified',
        'activated',
        'deactivated',
        'deleted'
    ];

    private $state;

    public static function fromNative($nativeValue): ValueObjectInterface
    {
        Assertion::nullOrInArray($nativeValue, self::STATES);
        return $nativeValue ? new self($nativeValue) : self::makeEmpty();
    }

    public function toNative()
    {
        return $this->state;
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        Assertion::isInstanceOf($otherValue, UserState::class);
        return $this->toNative() === $otherValue->toNative();
    }

    public function isUnverified(): bool
    {
        return $this->state === self::INITIAL;
    }

    public function isActivated(): bool
    {
        return $this->state === self::ACTIVATED;
    }

    public function isDeactivated(): bool
    {
        return $this->state === self::DEACTIVATED;
    }

    public function isDeleted(): bool
    {
        return $this->state === self::DELETED;
    }

    public function __toString(): string
    {
        return $this->toNative();
    }

    private function __construct(string $state)
    {
        $this->state = $state;
    }
}
