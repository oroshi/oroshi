<?php

declare(strict_types=1);

namespace Oro\Security\ReadModel\Standard;

use Daikon\ReadModel\Projection\ProjectionInterface;
use Daikon\ReadModel\Projection\ProjectionMap;
use Daikon\ReadModel\Query\QueryInterface;
use Daikon\ReadModel\Repository\RepositoryInterface;
use Daikon\ReadModel\Storage\StorageAdapterInterface;

final class UserRepository implements RepositoryInterface
{
    private $storageAdapter;

    public function __construct(StorageAdapterInterface $storageAdapter)
    {
        $this->storageAdapter = $storageAdapter;
    }

    public function findById(string $identifier): ProjectionInterface
    {
        return $this->storageAdapter->read($identifier);
    }

    public function findByIds(array $identifiers): ProjectionMap
    {
    }

    public function search(QueryInterface $query, int $from = null, int $size = null): ProjectionMap
    {
        return $this->storageAdapter->search($query, $from, $size);
    }

    public function persist(ProjectionInterface $projection): bool
    {
        return $this->storageAdapter->write($projection->getAggregateId(), $projection->toNative());
    }

    public function makeProjection(): ProjectionInterface
    {
        return User::fromNative([
            '@type' => User::class,
            '@parent' => null
        ]);
    }
}
