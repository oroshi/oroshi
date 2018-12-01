<?php

declare(strict_types=1);

use Aura\Router\RouterContainer;
use Auryn\Injector;
use Middlewares\RequestHandler;
use Middlewares\Whoops;
use Relay\Relay;
use Oroshi\Infrastructure\Bootstrap\WebBootstrap;
use Oroshi\Infrastructure\Middleware\AuraRouting;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
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

$middlewares = [];
if ($appDebug) {
    $middlewares[] = new Whoops(
        (new Run)->pushHandler(new PrettyPageHandler)
    );
}

array_push(
    $middlewares,
    $container->get(AuraRouting::class),
    new RequestHandler($container)
);

(new SapiStreamEmitter)->emit(
    (new Relay($middlewares))->handle(ServerRequestFactory::fromGlobals())
);
