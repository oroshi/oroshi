<?php

declare(strict_types=1);

namespace Oro\Security\ValueObject;

use Assert\Assertion;
use Daikon\Entity\ValueObject\ValueObjectInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

final class PasswordHash implements ValueObjectInterface
{
    const MAX_PASSWORD_LENGTH = 72;

    const DEFAULT_COST = 10;

    /** @var string */
    private $value;

    public static function gen(string $password, int $cost = self::DEFAULT_COST): PasswordHash
    {
        Assertion::maxLength($password, self::MAX_PASSWORD_LENGTH);
        return new self(self::encode($password, $cost));
    }

    /** @param string|null $value */
    public static function fromNative($value): PasswordHash
    {
        Assertion::nullOrString($value, 'Trying to create PasswordHash from unsupported value type.');
        return is_null($value) ? new PasswordHash : new PasswordHash($value);
    }

    public function equals(ValueObjectInterface $value): bool
    {
        return $value instanceof self && $this->toNative() === $value->toNative();
    }

    public function toNative(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toNative();
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function getLength(): int
    {
        return strlen($this->value);
    }

    public function verify(string $password)
    {
        Assertion::maxLength($password, self::MAX_PASSWORD_LENGTH);
        return password_verify($password, $this->value);
    }

    private function __construct(string $value)
    {
        Assertion::notEmpty($value);
        $this->value = $value;
    }

    private static function encode(string $password, int $cost): string
    {
        Assertion::between($cost, 4, 31, 'Cost must be in the range of 4-31.');
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    }
}
