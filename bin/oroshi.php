#!/usr/bin/env php
<?php

declare(strict_types=1);

//@todo move to debug context
ini_set('display_errors', 'on');

use Auryn\Injector;
use Oroshi\Core\Bootstrap\ConsoleBootstrap;
use Oroshi\Core\Console\Console;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

$baseDir = dirname(__DIR__);
/** @psalm-suppress UnresolvableInclude */
require_once "$baseDir/vendor/autoload.php";

$appDir = "$baseDir/app";
$appEnv = (new ArgvInput)->getParameterOption([ '--env', '-e' ], getenv('APP_ENV') ?: 'dev');
$appDebug = (new ArgvInput())->getParameterOption('--debug', getenv('APP_DEBUG') ?: true);

$container = (new ConsoleBootstrap)(new Injector, [
    'version' => getEnv('APP_VERSION') ?: 'master',
    'context' => 'console',
    'env' => $appEnv,
    'debug' => filter_var($appDebug, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
    'base_dir' => $baseDir,
    'crates_dir' => "$appDir/crates",
    'config_dir' => "$appDir/config",
    'secrets_dir' => getenv('SECRETS_DIR') ?: '/usr/local/env',
    'log_dir' => "$baseDir/var/logs",
    'cache_dir' => "$baseDir/var/cache"
]);

$container->make(Console::class)->run();
