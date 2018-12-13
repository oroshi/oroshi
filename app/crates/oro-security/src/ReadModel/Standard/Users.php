<?php

declare(strict_types=1);

namespace Oro\Security\ReadModel\Standard;

use Daikon\Elasticsearch6\Query\Elasticsearch6Query;
use Daikon\ReadModel\Repository\RepositoryMap;
use Oro\Security\ValueObject\RandomToken;

final class Users
{
    const REPOSITORY_KEY = 'oro.security.user.standard';

    /** @var UserRepository */
    private $userRepo;

    public function __construct(RepositoryMap $repositoryMap)
    {
        $this->userRepo = $repositoryMap->get(self::REPOSITORY_KEY);
    }

    public function byUsername(string $username): ?User
    {
        return $this->selectOne([
            'bool' => [
                'should' => [
                    ['term' => ['username' => $username]]
                ]
            ]
        ]);
    }

    public function byToken(RandomToken $token): ?User
    {
        return $this->selectOne([
            'bool' => [
                'should' => [
                    ['term' => ['tokens.token' => (string)$token]]
                ]
            ]
        ]);
    }

    private function selectOne(array $query): ?User
    {
        $foundUsers = $this->userRepo
            ->search(Elasticsearch6Query::fromNative(['query' => $query]), 0, 1)
            ->getIterator();

        return $foundUsers->valid() ? $foundUsers->current() : null;
    }
}
