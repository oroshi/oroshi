<?php

declare(strict_types=1);

namespace Oro\Security\ValueObject;

use Assert\Assertion;
use Daikon\Entity\ValueObject\ValueObjectInterface;

final class UserRole implements ValueObjectInterface
{
    private const NIL = '';

    private const ROLES = [
        'user',
        'administrator'
    ];

    private $role;

    public static function fromNative($nativeValue): ValueObjectInterface
    {
        Assertion::nullOrInArray($nativeValue, self::ROLES);
        return $nativeValue ? new self($nativeValue) : self::makeEmpty();
    }

    public function toNative()
    {
        return $this->role;
    }

    public static function makeEmpty(): ValueObjectInterface
    {
        return new self(self::NIL);
    }

    public function equals(ValueObjectInterface $otherValue): bool
    {
        Assertion::isInstanceOf($otherValue, UserRole::class);
        return $this->toNative() === $otherValue->toNative();
    }

    public function isEmpty(): bool
    {
        return $this->role === self::NIL;
    }

    public function __toString(): string
    {
        return $this->toNative();
    }

    private function __construct(string $role)
    {
        $this->role = $role;
    }
}
