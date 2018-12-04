<?php

declare(strict_types=1);

namespace Oro\Security\Api\Login;

use function GuzzleHttp\Psr7\parse_query;
use Oro\Security\Api\UserActionTrait;
use Oro\Security\ReadModel\Standard\User;
use Oroshi\Core\Middleware\ActionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class LoginAction implements ActionInterface
{
    use UserActionTrait;

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($user = $this->authenticate($request)) {
            return new JsonResponse(['token' => $this->userService->generateToken($user)]);
        }
        return new JsonResponse(['message' => 'access restriced - invalid credentials'], 401);
    }

    private function authenticate(ServerRequestInterface $request): ?User
    {
        $queryParams = parse_query($request->getUri()->getQuery());
        if (!isset($queryParams['username']) || !isset($queryParams['password'])) {
            return null;
        }
        return $this->userService->authenticate($queryParams['username'], $queryParams['password']);
    }
}
