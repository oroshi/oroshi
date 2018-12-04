<?php

declare(strict_types=1);

namespace Oro\Security\ValueObject;

use Assert\Assertion;
use Daikon\Entity\ValueObject\ValueObjectInterface;

final class RandomToken implements ValueObjectInterface
{
    private $token;

    public static function generate(): self
    {
        return new self(hash(
            'sha256',
            sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff)
            )
        ));
    }

    public static function fromNative($token): ValueObjectInterface
    {
        return new self($token);
    }

    public static function makeEmpty(): ValueObjectInterface
    {
        return new self(null);
    }

    public function toNative()
    {
        return $this->token;
    }

    public function equals(ValueObjectInterface $randomToken): bool
    {
        Assertion::isInstanceOf($randomToken, self::class);
        return $this->token === $randomToken->toNative();
    }

    public function isEmpty(): bool
    {
        return empty($this->token);
    }

    public function __toString(): string
    {
        return $this->token;
    }

    private function __construct(?string $token)
    {
        // @todo assert token pattern
        $this->token = $token;
    }
}
