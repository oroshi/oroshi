#!/usr/bin/env php
<?php

declare(strict_types=1);

use Auryn\Injector;
use Oroshi\Infrastructure\Bootstrap\ConsoleBootstrap;
use Oroshi\Infrastructure\Console\Command\ListCrates;
use Oroshi\Infrastructure\Console\Command\ListProjectors;
use Oroshi\Infrastructure\Console\Command\Migrate\ListTargets;
use Oroshi\Infrastructure\Console\Command\Migrate\MigrateDown;
use Oroshi\Infrastructure\Console\Command\Migrate\MigrateUp;
use Oroshi\Infrastructure\Console\Command\RunWorker;
use Oroshi\Infrastructure\Console\Console;
use Symfony\Component\Console\Application;

$baseDir = dirname(__DIR__);
require_once "$baseDir/vendor/autoload.php";

$appDir = "$baseDir/app";
$appDebug = getenv('APP_DEBUG') ?: true;
$container = (new ConsoleBootstrap)(new Injector, [
    'version' => getEnv('APP_VERSION') ?: 'master',
    'context' => 'console',
    'env' => getenv('APP_ENV') ?: 'dev',
    'debug' => filter_var($appDebug, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
    'base_dir' => $baseDir,
    'crates_dir' => "$appDir/crates",
    'config_dir' => "$appDir/config",
    'secrets_dir' => getenv('SECRETS_DIR') ?: '/usr/local/env',
    'log_dir' => "$baseDir/var/logs",
    'cache_dir' => "$baseDir/var/cache"
]);

$commands = [
    ListCrates::class,
    ListTargets::class,
    MigrateUp::class,
    MigrateDown::class,
    ListProjectors::class,
    RunWorker::class
];

$container->make(
    Console::class,
    [ ':commands' => array_map([ $container, 'get' ], $commands) ]
)->run();
