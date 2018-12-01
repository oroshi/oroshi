<?php

declare(strict_types=1);

namespace Oro\Testing\Article\Create;

use Daikon\EventSourcing\Aggregate\Command\Command;
use Daikon\Interop\FromToNativeTrait;
use Oro\Testing\Article\ArticleProperties;

/**
 * @map(aggregateId, Daikon\EventSourcing\Aggregate\AggregateId::fromNative)
 */
final class CreateArticle extends Command
{
    use FromToNativeTrait;
    use ArticleProperties;
}
