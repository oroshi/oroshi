<?php

use Oro\Security\Api\ActivateUser\ActivateUserAction;
use Oro\Security\Api\Login\LoginAction;
use Oro\Security\Api\Logout\LogoutAction;
use Oro\Security\Api\RegisterUser\RegisterUserAction;

$cratePrefix = 'oro.security';
$mount = $configProvider->get('crates.'.$cratePrefix.'.mount', '/oro/security');
$map->attach($cratePrefix, $mount, function ($map) use ($cratePrefix) {
    $map->get("$cratePrefix.user.login", '/user/login', LoginAction::class);
    $map->get("$cratePrefix.user.logout", '/user/logout', LogoutAction::class);
    $map->get("$cratePrefix.user.register", '/user/register', RegisterUserAction::class);
    $map->post("$cratePrefix.user.register.write", '/user/register', RegisterUserAction::class);
    $map->get("$cratePrefix.user.activate", '/user/activate', ActivateUserAction::class);
});
