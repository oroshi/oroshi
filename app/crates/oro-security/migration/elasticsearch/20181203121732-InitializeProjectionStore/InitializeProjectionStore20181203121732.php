<?php

declare(strict_types=1);

namespace Oro\Security\Migration\Elasticsearch;

use Daikon\Dbal\Migration\MigrationInterface;
use Daikon\Elasticsearch6\Migration\Elasticsearch6MigrationTrait;

final class InitializeProjectionStore20181203121732 implements MigrationInterface
{
    use Elasticsearch6MigrationTrait;

    public function getDescription(string $direction = self::MIGRATE_UP): string
    {
        return $direction === self::MIGRATE_UP
            ? 'Create the Elasticsearch migration list for the Oro-Security context.'
            : 'Delete the Elasticsearch migration list for the Oro-Security context.';
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
            'oro-security-migration_list' => $this->loadFile('migration_list-mapping-20181203121732.json')
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
