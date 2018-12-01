<?php

declare(strict_types=1);

namespace Oro\Testing\Article\Create;

use Daikon\EventSourcing\Aggregate\Event\DomainEvent;
use Daikon\EventSourcing\Aggregate\Event\DomainEventInterface;
use Daikon\Interop\FromToNativeTrait;
use Oro\Testing\Article\ArticleProperties;

/**
 * @map(aggregateId, Daikon\EventSourcing\Aggregate\AggregateId::fromNative)
 * @map(aggregateRevision, Daikon\EventSourcing\Aggregate\AggregateRevision::fromNative)
 */
final class ArticleWasCreated extends DomainEvent
{
    use ArticleProperties;
    use FromToNativeTrait;

    public static function fromCommand(CreateArticle $createArticle): self
    {
        return self::fromNative($createArticle->toNative());
    }

    public function conflictsWith(DomainEventInterface $otherEvent): bool
    {
        return false;
    }
}
