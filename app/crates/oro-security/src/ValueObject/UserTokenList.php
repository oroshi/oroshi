<?php

declare(strict_types=1);

namespace Oro\Security\ValueObject;

use Assert\Assertion;
use Daikon\Entity\Entity\EntityListInterface;
use Daikon\Entity\Entity\EntityListTrait;
use Dlx\Security\User\Domain\Entity\AuthToken;
use Dlx\Security\User\Domain\Entity\VerifyToken;
use Ds\Vector;

final class UserTokenList implements EntityListInterface
{
    use EntityListTrait;

    private function __construct(array $userTokens = [])
    {
        Assertion::allIsInstanceOf($userTokens, [ AuthToken::class, VerifyToken::class ]);
        $this->compositeVector = new Vector($userTokens);
    }
}
