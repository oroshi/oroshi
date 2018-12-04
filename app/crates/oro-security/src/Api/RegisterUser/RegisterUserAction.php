<?php

declare(strict_types=1);

namespace Oro\Security\Api\RegisterUser;

use Assert\Assertion;
use Oro\Security\Api\AbstractUserAction;
use Oro\Security\Api\UserActionTrait;
use Oroshi\Core\Middleware\ActionInterface;
use Oroshi\Core\Middleware\ValidationInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class RegisterUserAction implements ActionInterface
{
    use UserActionTrait;

    const ATTR_INFOS = 'userInfos';

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->hasError($request)) {
            return $this->errorResponse('Invalid registration request-data.', $request);
        }
        $userInfos = $request->getAttribute(self::ATTR_INFOS);
        if ($this->userService->register($userInfos)) {
            return new JsonResponse(['message' => 'Successfully registered user.']);
        }
        return new JsonResponse(['message' => 'Failed to register user.']);
    }

    public function isSecure(): bool
    {
        return false;
    }

    public function getValidation(): ?ValidationInterface
    {
        return $this->makeValidation(
            RegistrationValidator::class,
            [':attribute' => self::ATTR_INFOS]
        );
    }
}
