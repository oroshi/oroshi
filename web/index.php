<?php

declare(strict_types=1);

ini_set('display_errors', 'on');
ini_set('xdebug.default_enable', 'on');

use Auryn\Injector;
use Oroshi\Core\Bootstrap\WebBootstrap;
use Oroshi\Core\Middleware\PipelineBuilderInterface;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiStreamEmitter;

$baseDir = dirname(__DIR__);
require_once "$baseDir/vendor/autoload.php";

$appDir = "$baseDir/app";
$appDebug = getenv('APP_DEBUG') ?: true;
$container = (new WebBootstrap)(new Injector, [
    'version' => getEnv('APP_VERSION') ?: 'master',
    'context' => 'web',
    'env' => getenv('APP_ENV') ?: 'dev',
    'debug' => filter_var($appDebug, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
    'base_dir' => $baseDir,
    'crates_dir' => "$appDir/crates",
    'config_dir' => "$appDir/config",
    'secrets_dir' => getenv('SECRETS_DIR') ?: '/usr/local/env',
    'log_dir' => "$baseDir/var/logs",
    'cache_dir' => "$baseDir/var/cache"
]);

$middlewareHandler = $container->get(PipelineBuilderInterface::class)();
(new SapiStreamEmitter)->emit(
    $middlewareHandler->handle(ServerRequestFactory::fromGlobals())
);
