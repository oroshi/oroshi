<?php

declare(strict_types=1);

namespace Oroshi\Service\Provisioner;

use Auryn\Injector;
use Daikon\Config\ConfigProviderInterface;
use Daikon\Dbal\Connector\ConnectorInterface;
use Daikon\Dbal\Connector\ConnectorMap;
use Oroshi\Service\ServiceDefinitionInterface;

final class ConnectorMapProvisioner implements ProvisionerInterface
{
    public function provision(
        Injector $injector,
        ConfigProviderInterface $configProvider,
        ServiceDefinitionInterface $serviceDefinition
    ): void {
        $injector
            ->share(ConnectorMap::class)
            ->delegate(
                ConnectorMap::class,
                $this->factory(
                    $injector,
                    $configProvider->get('connectors', [])
                )
            );
    }

    private function factory(Injector $injector, array $connectorConfigs): callable
    {
        return function () use ($injector, $connectorConfigs): ConnectorMap {
            $connectors = [];
            foreach ($connectorConfigs as $connectorName => $connectorConfig) {
                if (isset($connectorConfig['connector'])) {
                    $connectorConfig = array_replace_recursive(
                        $connectorConfigs[$connectorConfig['connector']],
                        $connectorConfig
                    );
                }
                $connectorClass = $connectorConfig['class'];
                $connectors[$connectorName] = $injector->make(
                    $connectorClass,
                    [':settings' => $connectorConfig['settings'] ?? []]
                );
            }
            return new ConnectorMap($connectors);
        };
    }
}