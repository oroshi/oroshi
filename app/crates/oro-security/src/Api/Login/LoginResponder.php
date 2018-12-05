<?php

declare(strict_types=1);

namespace Oro\Security\Api\Login;

use Oroshi\Core\Middleware\Action\ResponderInterface;
use Oroshi\Core\Middleware\Action\ResponderTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class LoginResponder implements ResponderInterface
{
    use ResponderTrait;

    public function respondToJson(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['message' => 'Successfully logged in.']);
    }

    public function respondToHtml(ServerRequestInterface $request): ResponseInterface
    {
        return $this->respondToJson($request);
    }
}
