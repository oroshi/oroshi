<?php

declare(strict_types=1);

namespace Oroshi\Infrastructure\Service\Provisioner;

use Auryn\Injector;
use Daikon\Config\ConfigProviderInterface;
use Daikon\Dbal\Connector\ConnectorMap;
use Daikon\Dbal\Storage\StorageAdapterMap;
use Oroshi\Infrastructure\Service\ServiceDefinitionInterface;

final class StorageAdapterMapProvisioner implements ProvisionerInterface
{
    public function provision(
        Injector $injector,
        ConfigProviderInterface $configProvider,
        ServiceDefinitionInterface $serviceDefinition
    ): void {
        $adapterConfigs = $configProvider->get('databases.storage_adapters', []);
        $factory = function (ConnectorMap $connectorMap) use ($injector, $adapterConfigs): StorageAdapterMap {
            $adapters = [];
            foreach ($adapterConfigs as $adapterName => $adapterConfigs) {
                $adapterClass = $adapterConfigs['class'];
                $adapters[$adapterName] = $injector->make(
                    $adapterClass,
                    [
                        ':connector' => $connectorMap->get($adapterConfigs['connector']),
                        ':settings' => $adapterConfigs['settings'] ?? []
                    ]
                );
            }
            return new StorageAdapterMap($adapters);
        };

        $injector
            ->share(StorageAdapterMap::class)
            ->delegate(StorageAdapterMap::class, $factory);
    }
}