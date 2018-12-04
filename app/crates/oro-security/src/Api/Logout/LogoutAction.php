<?php

declare(strict_types=1);

namespace Oro\Security\Api\Logout;

use Oro\Security\Api\UserActionTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class LogoutAction implements ActionInterface
{
    use UserActionTrait;

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['message' => 'not implemented yet']);
    }

    public function handleError(ServerRequestInterface $request): ResponseInterface
    {
        return $this->errorResponse('Invalid logout request-data.', $request);
    }
}
