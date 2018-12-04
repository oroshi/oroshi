<?php

declare(strict_types=1);

namespace Oro\Security\Migration\Elasticsearch;

use Daikon\Dbal\Migration\MigrationInterface;
use Daikon\Elasticsearch5\Migration\Elasticsearch5MigrationTrait;

final class InitViewStore20181203121732 implements MigrationInterface
{
    use Elasticsearch5MigrationTrait;

    public function getDescription(string $direction = self::MIGRATE_UP): string
    {
        return $direction === self::MIGRATE_UP
            ? 'Create the Elasticsearch index for the Oro-Security context.'
            : 'Delete the Elasticsearch index for the Oro-Security context.';
    }

    public function isReversible(): bool
    {
        return true;
    }

    private function up(): void
    {
        $alias = $this->getIndexName();
        $index = sprintf('%s.%d', $alias, $this->getVersion());
        $this->createIndex($index, $this->loadFile('index-settings.json'));
        $this->createAlias($index, $alias);
        $this->putMappings($index, [
            'oro-security-migration_list' => $this->loadFile('migration_list-mapping-20181203121732.json')
        ]);
    }

    private function down(): void
    {
        $this->deleteIndex($this->getIndexName());
    }

    private function loadFile(string $filename): array
    {
        return json_decode(file_get_contents(__DIR__.'/'.$filename), true);
    }
}
