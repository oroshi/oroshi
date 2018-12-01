<?php

namespace Oro\Testing\Migration\RabbitMq;

use Daikon\Dbal\Migration\MigrationInterface;
use Daikon\RabbitMq3\Migration\RabbitMq3MigrationTrait;

final class InitializeMessageQueue20170707181818 implements MigrationInterface
{
    use RabbitMq3MigrationTrait;

    public function getDescription(string $direction = self::MIGRATE_UP): string
    {
        return $direction === self::MIGRATE_UP
            ? 'Create a RabbitMQ message pipeline for the Oro-Testing context.'
            : 'Delete the RabbitMQ message pipeline for the Oro-Testing context.';
    }

    public function isReversible(): bool
    {
        return true;
    }

    private function up(): void
    {
        $this->createMigrationList('oro.testing.migration_list');
        $this->createMessagePipeline('oro.testing.messages');
    }

    private function down(): void
    {
        $this->deleteMessagePipeline('oro.testing.messages');
        $this->deleteExchange('oro.testing.migration_list');
    }
}
