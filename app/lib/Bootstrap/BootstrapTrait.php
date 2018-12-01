<?php

declare(strict_types=1);

namespace Oroshi\Bootstrap;

use Daikon\Config\ArrayConfigLoader;
use Daikon\Config\ConfigProvider;
use Daikon\Config\ConfigProviderInterface;
use Daikon\Config\ConfigProviderParams;
use Daikon\Config\YamlConfigLoader;
use Oroshi\Config\CratesConfigLoader;

trait BootstrapTrait
{
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
                                'vendor:' => $bootParams['base_dir']."/vendor"
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