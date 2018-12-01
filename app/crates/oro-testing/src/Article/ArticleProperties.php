<?php

declare(strict_types=1);

namespace Oro\Testing\Article;

use Daikon\Entity\ValueObject\Text;

/**
 * @map(title, Daikon\Entity\ValueObject\Text::fromNative)
 * @map(content, Daikon\Entity\ValueObject\Text::fromNative)
 */
trait ArticleProperties
{
    /** @var Text */
    private $title;

    /** @var Text */
    private $content;

    public function getTitle(): Text
    {
        return $this->title;
    }

    public function getContent(): Text
    {
        return $this->title;
    }
}
