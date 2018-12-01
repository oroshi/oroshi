<?php

declare(strict_types=1);

namespace Oro\Testing\Article;

use Daikon\EventSourcing\Aggregate\AggregateRootInterface;
use Daikon\EventSourcing\Aggregate\AggregateRootTrait;
use Oro\Testing\Article\Create\ArticleWasCreated;
use Oro\Testing\Article\Create\CreateArticle;
use Oro\Testing\Article\Update\ArticleWasUpdated;
use Oro\Testing\Article\Update\UpdateArticle;

final class Article implements AggregateRootInterface
{
    use AggregateRootTrait;
    use ArticleProperties;

    public static function create(CreateArticle $createArticle): self
    {
        return (new self($createArticle->getAggregateId()))
            ->reflectThat(ArticleWasCreated::fromCommand($createArticle));
    }

    public function update(UpdateArticle $updateArticle): self
    {
        return $this->reflectThat(ArticleWasUpdated::fromCommand($updateArticle));
    }

    protected function whenArticleWasCreated(ArticleWasCreated $articleWasCreated)
    {
        $this->title = $articleWasCreated->getTitle();
        $this->content = $articleWasCreated->getContent();
    }

    protected function whenArticleWasUpdated(ArticleWasUpdated $articleWasUpdated)
    {
        $this->title = $articleWasUpdated->getTitle();
        $this->content = $articleWasUpdated->getContent();
    }
}
