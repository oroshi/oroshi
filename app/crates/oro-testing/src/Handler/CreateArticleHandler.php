<?php

declare(strict_types=1);

namespace Oro\Testing\Handler;

use Daikon\EventSourcing\Aggregate\Command\CommandHandler;
use Daikon\MessageBus\Metadata\Metadata;
use Oro\Testing\Article\Article;
use Oro\Testing\Article\Create\CreateArticle;

final class CreateArticleHandler extends CommandHandler
{
    protected function handleCreateArticle(CreateArticle $createArticle, Metadata $metadata): array
    {
        return [ Article::create($createArticle), $metadata ];
    }
}
