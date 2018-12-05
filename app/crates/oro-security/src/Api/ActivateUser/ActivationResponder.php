<?php

declare(strict_types=1);

namespace Oro\Security\Api\ActivateUser;

use Oroshi\Core\Middleware\Action\ResponderInterface;
use Oroshi\Core\Middleware\Action\ResponderTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class ActivationResponder implements ResponderInterface
{
    use ResponderTrait;

    public function respondToJson(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['message' => 'Successfully activated user.']);
    }

    public function respondToHtml(ServerRequestInterface $request): ResponseInterface
    {
        return $this->respondToJson($request);
    }
}
