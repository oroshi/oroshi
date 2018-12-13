<?php

namespace Oro\Testing\Migration\Elasticsearch;

use Daikon\Dbal\Migration\MigrationInterface;
use Daikon\Elasticsearch6\Migration\Elasticsearch6MigrationTrait;

final class InitializeProjectionStore20170707181818 implements MigrationInterface
{
    use Elasticsearch6MigrationTrait;

    public function getDescription(string $direction = self::MIGRATE_UP): string
    {
        return $direction === self::MIGRATE_UP
            ? 'Create the Elasticsearch migration list for the Oro-Testing context.'
            : 'Delete the Elasticsearch migration list for the Oro-Testing context.';
    }

    public function isReversible(): bool
    {
        return true;
    }

    private function up(): void
    {
        $index = "{$this->getIndexName()}.migration_list";
        $this->createIndex($index, $this->loadFile('index-settings.json'));
        $this->putMappings($index, [
            'oro-testing-migration_list' => $this->loadFile('migration_list-mapping-20170707181818.json')
        ]);
    }

    private function down(): void
    {
        $index = "{$this->getIndexName()}.migration_list";
        $this->deleteIndex($index);
    }

    private function loadFile(string $filename): array
    {
        return json_decode(file_get_contents(__DIR__.'/'.$filename), true);
    }
}
