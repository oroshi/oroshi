<?php

declare(strict_types=1);

namespace Oro\Security\CommandHandler;

use Daikon\EventSourcing\Aggregate\Command\CommandHandler;
use Daikon\MessageBus\Metadata\Metadata;
use Oro\Security\User\Activate\ActivateUser;

final class ActivateUserHandler extends CommandHandler
{
    protected function handleActivateUser(ActivateUser $activateUser, Metadata $metadata): array
    {
        $user = $this->checkout(
            $activateUser->getAggregateId(),
            $activateUser->getKnownAggregateRevision()
        );
        return [ $user->activate($activateUser), $metadata ];
    }
}
