<?php

declare(strict_types=1);

namespace Oroshi\Service\Provisioner;

use Auryn\Injector;
use Daikon\Config\ConfigProviderInterface;
use Oroshi\Service\ServiceDefinitionInterface;

interface ProvisionerInterface
{
    public function provision(
        Injector $injector,
        ConfigProviderInterface $configProvider,
        ServiceDefinitionInterface $serviceDefinition
    ): void;
}
