<?php

declare(strict_types=1);

namespace Oro\Security\Migration\CouchDb;

use Daikon\CouchDb\Migration\CouchDbMigrationTrait;
use Daikon\Dbal\Migration\MigrationInterface;

final class InitEventStore20181203121037 implements MigrationInterface
{
    use CouchDbMigrationTrait;

    public function getDescription(string $direction = self::MIGRATE_UP): string
    {
        return $direction === self::MIGRATE_UP
            ? 'Create the CouchDb database for the Oro-Security context.'
            : 'Delete the CouchDb database for the Oro-Security context.';
    }

    public function isReversible(): bool
    {
        return true;
    }

    private function up(): void
    {
        $this->createDatabase($this->getDatabaseName());
    }

    private function down(): void
    {
        $this->deleteDatabase($this->getDatabaseName());
    }
}
