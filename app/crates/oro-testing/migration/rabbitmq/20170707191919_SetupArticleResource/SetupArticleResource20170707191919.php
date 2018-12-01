<?php

namespace Oro\Testing\Migration\RabbitMq;

use Daikon\Dbal\Migration\MigrationInterface;
use Daikon\RabbitMq3\Migration\RabbitMq3MigrationTrait;

final class SetupArticleResource20170707191919 implements MigrationInterface
{
    use RabbitMq3MigrationTrait;

    public function getDescription(string $direction = self::MIGRATE_UP): string
    {
        return $direction === self::MIGRATE_UP
            ? 'Create RabbitMQ message queue for Article resource events.'
            : 'Delete RabbitMQ message queue for Article resource events.';
    }

    public function isReversible(): bool
    {
        return true;
    }

    private function up(): void
    {
        $this->declareQueue('oro.testing.article.messages', false, true, false, false);
        $this->bindQueue('oro.testing.article.messages', 'oro.testing.messages', 'oro.testing.article.#');
    }

    private function down(): void
    {
        $this->deleteQueue('oro.testing.article.messages');
    }
}
