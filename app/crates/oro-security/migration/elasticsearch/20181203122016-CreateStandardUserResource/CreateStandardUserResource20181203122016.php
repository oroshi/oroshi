<?php

declare(strict_types=1);

namespace Oro\Security\Migration\Elasticsearch;

use Daikon\Dbal\Migration\MigrationInterface;
use Daikon\Elasticsearch6\Migration\Elasticsearch6MigrationTrait;

final class CreateStandardUserResource20181203122016 implements MigrationInterface
{
    use Elasticsearch6MigrationTrait;

    public function getDescription(string $direction = self::MIGRATE_UP): string
    {
        return $direction === self::MIGRATE_UP
            ? 'Create the User resource standard projection Elasticsearch index.'
            : 'Delete the User resource standard projection Elasticsearch index.';
    }

    public function isReversible(): bool
    {
        return true;
    }

    private function up(): void
    {
        $alias = $this->getIndexPrefix().'.user.standard';
        $index = sprintf('%s.%d', $alias, $this->getVersion());
        $this->createIndex($index, $this->loadFile('index-settings.json'));
        $this->createAlias($index, $alias);
        $this->putMappings(
            $alias,
            ['oro-security-user-standard' => $this->loadFile('user-standard-mapping-20181203122016.json')]
        );
    }

    private function down(): void
    {
        $alias = $this->getIndexPrefix().'.user.standard';
        $index = sprintf('%s.%d', $alias, $this->getVersion());
        $this->deleteIndex($index);
    }

    private function loadFile(string $filename): array
    {
        return json_decode(file_get_contents(__DIR__.'/'.$filename), true);
    }
}
