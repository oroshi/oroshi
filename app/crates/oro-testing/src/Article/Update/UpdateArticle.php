<?php

declare(strict_types=1);

namespace Oro\Testing\Article\Update;

use Daikon\EventSourcing\Aggregate\Command\Command;
use Daikon\Interop\FromToNativeTrait;
use Oro\Testing\Article\ArticleProperties;

/**
 * @map(aggregateId, Daikon\EventSourcing\Aggregate\AggregateId::fromNative)
 * @map(knownAggregateRevision, Daikon\EventSourcing\Aggregate\AggregateRevision::fromNative)
 */
final class UpdateArticle extends Command
{
    use FromToNativeTrait;
    use ArticleProperties;
}
