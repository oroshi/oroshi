<?php

declare(strict_types=1);

namespace Oro\Security\User\Register;

use Daikon\EventSourcing\Aggregate\Command\Command;
use Daikon\Interop\FromToNativeTrait;

/**
 * @map(aggregateId, Daikon\EventSourcing\Aggregate\AggregateId::fromNative)
 */
final class RegisterUser extends Command
{
    use FromToNativeTrait;
    use RegisterTrait;
}
