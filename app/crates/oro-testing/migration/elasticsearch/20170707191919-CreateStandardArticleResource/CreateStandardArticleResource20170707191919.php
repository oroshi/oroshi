<?php

namespace Oro\Testing\Migration\Elasticsearch;

use Daikon\Dbal\Migration\MigrationInterface;
use Daikon\Elasticsearch6\Migration\Elasticsearch6MigrationTrait;

final class CreateStandardArticleResource20170707191919 implements MigrationInterface
{
    use Elasticsearch6MigrationTrait;

    public function getDescription(string $direction = self::MIGRATE_UP): string
    {
        return $direction === self::MIGRATE_UP
            ? 'Create the Article resource standard projection Elasticsearch index.'
            : 'Delete the Article resource standard projection Elasticsearch index.';
    }

    public function isReversible(): bool
    {
        return true;
    }

    private function up(): void
    {
        $alias = $this->getAlias();
        $index = sprintf('%s.%d', $alias, $this->getVersion());
        $this->createIndex($index, $this->loadFile('index-settings.json'));
        $this->createAlias($index, $alias);
        $this->putMappings(
            $alias,
            ['oro-testing-article-standard' => $this->loadFile('article-standard-mapping-20170707191919.json')]
        );
    }

    private function down(): void
    {
        $index = current($this->getIndicesWithAlias($this->getAlias()));
        $this->deleteIndex($index);
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
