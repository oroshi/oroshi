<?php

declare(strict_types=1);

namespace Oroshi\Infrastructure\Bootstrap;

use Auryn\Injector;
use Psr\Container\ContainerInterface;

interface BootstrapInterface
{
    public function __invoke(Injector $injector, array $bootstrapParams): ContainerInterface;
}