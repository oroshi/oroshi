<?php

declare(strict_types=1);

namespace Oro\Security\CommandHandler;

use Daikon\EventSourcing\Aggregate\Command\CommandHandler;
use Daikon\MessageBus\Metadata\Metadata;
use Oro\Security\User\Register\RegisterUser;
use Oro\Security\User\User;

final class RegisterUserHandler extends CommandHandler
{
    protected function handleRegisterUser(RegisterUser $registerUser, Metadata $metadata): array
    {
        return [User::register($registerUser), $metadata];
    }
}
