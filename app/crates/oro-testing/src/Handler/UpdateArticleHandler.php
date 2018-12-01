<?php

declare(strict_types=1);

namespace Oro\Testing\Handler;

use Daikon\EventSourcing\Aggregate\Command\CommandHandler;
use Daikon\MessageBus\Metadata\Metadata;
use Oro\Testing\Article\Update\UpdateArticle;

final class UpdateArticleHandler extends CommandHandler
{
    protected function handleUpdateArticle(UpdateArticle $updateArticle, Metadata $metadata): array
    {
        $article = $this->checkout(
            $updateArticle->getAggregateId(),
            $updateArticle->getKnownAggregateRevision()
        );
        return [ $article->update($updateArticle), $metadata ];
    }
}
