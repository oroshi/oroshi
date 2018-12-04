<?php

declare(strict_types=1);

namespace Oro\Security\Api\ActivateUser;

use function GuzzleHttp\Psr7\parse_query;
use Oro\Security\Api\UserActionTrait;
use Oro\Security\ReadModel\Standard\User;
use Oro\Security\ValueObject\RandomToken;
use Oroshi\Core\Middleware\ActionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class ActivateUserAction implements ActionInterface
{
    use UserActionTrait;

    const ATTR_TOKEN = 'token';

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $token = $request->getAttribute(self::ATTR_TOKEN);
        if ($user = $this->userService->activate($token)) {
            return new JsonResponse(['message' => 'Successfully activated user.']);
        }
        return $this->errorResponse('Failed to activate user.');
    }

    public function handleError(ServerRequestInterface $request): ResponseInterface
    {
        return $this->errorResponse('Invalid activation request-data.', $request);
    }

    public function getValidation(): ?ValidationInterface
    {
        return $this->makeValidation(
            ActivationValidator::class,
            [':attribute' => self::ATTR_TOKEN]
        );
    }

    public function isSecure(): bool
    {
        return false;
    }
}
