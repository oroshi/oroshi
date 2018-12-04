<?php

declare(strict_types=1);

namespace Oro\Security\Api\RegisterUser;

use Assert\Assertion;
use Oro\Security\Api\AbstractUserAction;
use Oro\Security\Api\UserActionTrait;
use Oroshi\Core\Middleware\Action\ActionInterface;
use Oroshi\Core\Middleware\ActionHandler;
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
        $userInfos = $request->getAttribute(self::ATTR_INFOS);
        if ($this->userService->register($userInfos)) {
            return new JsonResponse(['message' => 'Successfully registered user.']);
        }
        return $this->errorResponse('Failed to register user.');
    }

    public function registerValidator(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request->withAttribute(
            ActionHandler::ATTR_VALIDATOR,
            [RegistrationValidator::class, [':exportTo' => self::ATTR_INFOS]]
        );
    }

    public function handleError(ServerRequestInterface $request): ResponseInterface
    {
        return $this->errorResponse('Invalid registration request-data.', $request);
    }

    public function isSecure(): bool
    {
        return false;
    }
}
