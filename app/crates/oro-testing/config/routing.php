<?php

use Oro\Testing\Api\CreateArticleAction;
use Oro\Testing\Api\UpdateArticleAction;

$cratePrefix = 'oro.testing';
$mount = $configProvider->get("crates.$cratePrefix.mount", '/oro/testing');
$map->attach("$cratePrefix.", $mount, function ($map) use ($cratePrefix) {
    $map->get('article.create', '/article/create', CreateArticleAction::class);
    $map->get('article.update', '/article/update', UpdateArticleAction::class);
});
