<?php

namespace Oro\Testing\Migration\Elasticsearch;

use Daikon\Dbal\Migration\MigrationInterface;
use Daikon\Elasticsearch6\Migration\Elasticsearch6MigrationTrait;

final class UpdateStandardArticleResource20181213191919 implements MigrationInterface
{
    use Elasticsearch6MigrationTrait;

    public function getDescription(string $direction = self::MIGRATE_UP): string
    {
        return $direction === self::MIGRATE_UP
            ? 'Update the Article resource standard projection Elasticsearch index.'
            : 'Revert the Article resource standard projection Elasticsearch index.';
    }

    public function isReversible(): bool
    {
        return true;
    }

    private function up(): void
    {
        $alias = $this->getAlias();
        $currentIndex = current($this->getIndicesWithAlias($alias));
        $newIndex = sprintf('%s.%d', $alias, $this->getVersion());
        $this->reindexWithMappings(
            $currentIndex,
            $newIndex,
            ['oro-testing-article-standard' => $this->loadFile('article-standard-mapping-20181213191919.json')]
        );
        $this->reassignAlias($newIndex, $alias);
        $this->deleteIndex($currentIndex);
    }

    private function down(): void
    {
        $alias = $this->getAlias();
        $currentIndex = current($this->getIndicesWithAlias($alias));
        $revertedIndex = $currentIndex.'.reverted';
        $this->reindexWithMappings(
            $currentIndex,
            $revertedIndex,
            ['oro-testing-article-standard' => $this->loadFile(
                '../20170707191919-CreateStandardArticleResource/article-standard-mapping-20170707191919.json'
            )]
        );
        $this->reassignAlias($revertedIndex, $alias);
        $this->deleteIndex($currentIndex);
    }

    private function loadFile(string $filename): array
    {
        return json_decode(file_get_contents(__DIR__.'/'.$filename), true);
    }

    private function getAlias(): string
    {
        return $this->getIndexPrefix().'.article.standard';
    }
}
