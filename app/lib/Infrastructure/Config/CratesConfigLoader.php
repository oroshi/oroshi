<?php

declare(strict_types=1);

namespace Oroshi\Infrastructure\Config;

use Daikon\Config\ConfigLoaderInterface;
use Daikon\Config\YamlConfigLoader;
use Stringy\Stringy;

final class CratesConfigLoader implements ConfigLoaderInterface
{
    /** @var YamlConfigLoader */
    private $yamlLoader;

    /** @var array */
    private $dirPrefixes;

    public function __construct(array $dirPrefixes, YamlConfigLoader $yamlLoader = null)
    {
        $this->yamlLoader = $yamlLoader ?? new YamlConfigLoader;
        $this->dirPrefixes = $dirPrefixes;
    }

    /**
     * @param array $locations
     * @param array $sources
     * @return mixed[]
     */
    public function load(array $locations, array $sources): array
    {
        $config = [];
        foreach ($this->yamlLoader->load($locations, $sources) as $crateName => $crateConfig) {
            $configDir = $crateConfig['config_dir'];
            $migrationDir = $crateConfig['migration_dir'];
            $crateConfig['config_dir'] = $this->expandPath($configDir);
            $crateConfig['migration_dir'] = $this->expandPath($migrationDir);
            $config[$crateName] = $crateConfig;
        }
        return $config;
    }

    private function expandPath(string $path): string
    {
        if (Stringy::create($path)->startsWith('/')) {
            return $path;
        }
        $search = array_keys($this->dirPrefixes);
        $replace = array_map(function (string $path): string {
            return Stringy::create($path)->endsWith('/') ? $path : "$path/";
        }, array_values($this->dirPrefixes));
        return str_replace($search, $replace, $path);
    }
}
