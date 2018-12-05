<?php

declare(strict_types=1);

namespace Oro\Security\ValueObject;

use Assert\Assertion;
use Daikon\Entity\Entity\EntityInterface;
use Daikon\Entity\Entity\EntityListInterface;
use Daikon\Entity\Entity\EntityListTrait;
use Ds\Vector;
use Oro\Security\Entity\AuthToken;
use Oro\Security\Entity\VerifyToken;
use RuntimeException;

final class UserTokenList implements EntityListInterface
{
    use EntityListTrait;

    private function __construct(array $userTokens = [])
    {
        $this->compositeVector = new Vector(array_map([$this, 'guardTokenType'], $userTokens));
    }

    public function byType(string $typeFqcn): ?EntityInterface
    {
        Assertion::classExists($typeFqcn);
        foreach ($this->compositeVector as $token) {
            if ($token instanceof $typeFqcn) {
                return $token;
            }
        }
        return null;
    }

    public function remove(EntityInterface $token): self
    {
        $tokens = [];
        foreach ($this->compositeVector as $curToken) {
            if (!$token->equals($curToken)) {
                $tokens[] = $curToken;
            }
        }
        return new self($tokens);
    }

    private function guardTokenType(EntityInterface $token): EntityInterface
    {
        if (!$token instanceof AuthToken && !$token instanceof VerifyToken) {
            throw new RuntimeException('Given token type is not supported!');
        }
        return $token;
    }
}
