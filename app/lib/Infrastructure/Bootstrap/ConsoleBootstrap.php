<?php

declare(strict_types=1);

namespace Oroshi\Infrastructure\Bootstrap;

use Auryn\Injector;
use Daikon\Config\ArrayConfigLoader;
use Daikon\Config\ConfigProvider;
use Daikon\Config\ConfigProviderInterface;
use Daikon\Config\ConfigProviderParams;
use Daikon\Config\YamlConfigLoader;
use Psr\Container\ContainerInterface;
use Oroshi\Infrastructure\Config\CratesConfigLoader;
use Oroshi\Infrastructure\Service\ServiceProvisioner;

final class ConsoleBootstrap implements BootstrapInterface
{
    public function __invoke(Injector $injector, array $bootParams): ContainerInterface
    {
        $configProvider = $this->loadConfiguration($bootParams);
        $injector
            ->share($configProvider)
            ->alias(ConfigProviderInterface::class, ConfigProvider::class);

        return (new ServiceProvisioner)->provision($injector, $configProvider);
    }

    private function loadConfiguration(array $bootParams): ConfigProviderInterface
    {
        return new ConfigProvider(
            new ConfigProviderParams(
                array_merge(
                    [
                        'app' => [
                            'loader' => ArrayConfigLoader::class,
                            'sources' => $bootParams
                        ],
                        'crates' => [
                            'loader' => new CratesConfigLoader([
                                'crates:' => $bootParams['crates_dir'],
                                'vendor:' => $bootParams['base_dir'].'/vendor'
                            ]),
                            'locations' => [ $bootParams['config_dir'] ],
                            'sources' => [ 'crates.yml' ]
                        ]
                    ],
                    (new YamlConfigLoader)->load(
                        [ $bootParams['config_dir'] ],
                        [ 'loaders.yml' ]
                    )
                )
            )
        );
    }
}
