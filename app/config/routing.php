<?php

use Zend\Diactoros\Response\JsonResponse;

$map->get('index', '/', function () {
    return new JsonResponse([
        'status' => 'yay',
        'message' => 'index action closure!'
    ]);
});
