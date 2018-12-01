<?php

declare(strict_types=1);

namespace Oroshi\Infrastructure\Service\Provisioner;

use Auryn\Injector;
use Daikon\Config\ConfigProviderInterface;
use Oroshi\Infrastructure\Crate\Crate;
use Oroshi\Infrastructure\Crate\CrateMap;
use Oroshi\Infrastructure\Service\ServiceDefinitionInterface;
use Stringy\Stringy;

final class CrateMapProvisioner implements ProvisionerInterface
{
    public function provision(
        Injector $injector,
        ConfigProviderInterface $configProvider,
        ServiceDefinitionInterface $serviceDefinition
    ): void {
        $crateConfigs = $configProvider->get('crates', []);
        $cratesDir = $configProvider->get('app.crates_dir');
        $factory = function () use ($crateConfigs, $cratesDir): CrateMap {
            $crates = [];
            foreach ($crateConfigs as $crateName => $crateConfig) {
                $crates[$crateName] = new Crate($crateConfig);
            }
            return new CrateMap($crates);
        };

        $injector
            ->share(CrateMap::class)
            ->delegate(CrateMap::class, $factory);
    }
}
