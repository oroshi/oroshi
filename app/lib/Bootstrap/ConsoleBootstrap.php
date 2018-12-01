<?php

declare(strict_types=1);

namespace Oroshi\Bootstrap;

use Auryn\Injector;
use Daikon\Config\ArrayConfigLoader;
use Daikon\Config\ConfigProvider;
use Daikon\Config\ConfigProviderInterface;
use Daikon\Config\ConfigProviderParams;
use Daikon\Config\YamlConfigLoader;
use Oroshi\Config\CratesConfigLoader;
use Oroshi\Console\Command\ListCrates;
use Oroshi\Console\Command\ListProjectors;
use Oroshi\Console\Command\Migrate\ListTargets;
use Oroshi\Console\Command\Migrate\MigrateDown;
use Oroshi\Console\Command\Migrate\MigrateUp;
use Oroshi\Console\Command\RunWorker;
use Oroshi\Service\ServiceProvisioner;
use Psr\Container\ContainerInterface;

final class ConsoleBootstrap implements BootstrapInterface
{
    use BootstrapTrait;

    public function __invoke(Injector $injector, array $bootParams): ContainerInterface
    {
        $configProvider = $this->loadConfiguration($bootParams);
        $injector
            ->share($configProvider)
            ->alias(ConfigProviderInterface::class, ConfigProvider::class);
        $container = (new ServiceProvisioner)->provision($injector, $configProvider);
        $injector
            ->share($container)
            ->alias(ContainerInterface::class, get_class($container))
            ->defineParam(
                'consoleCommands',
                array_map([ $container, 'get' ], [
                    ListCrates::class,
                    ListTargets::class,
                    MigrateUp::class,
                    MigrateDown::class,
                    ListProjectors::class,
                    RunWorker::class
                ])
            );
        return $container;
    }
}
