<?php

declare(strict_types=1);

namespace Oro\Security\Api\RegisterUser;

use Assert\Assertion;
use Oro\Security\Api\AbstractUserAction;
use Oro\Security\Api\ErrorResponder;
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

    public function __invoke(ServerRequestInterface $request): ServerRequestInterface
    {
        try {
            $registration = $this->userService->register($request->getAttribute(self::ATTR_INFOS));

            $responder = RegistrationResponder::class;
            $params = [':registration' => $registration];
        } catch (\Exception $error) {
            $errorMsg = 'An unexpected error occured during registration.';
            $this->logger->error($errorMsg, ['exception' => $error]);

            $responder = ErrorResponder::class;
            $params = [':message' => $errorMsg];
        }
        return $request->withAttribute(ActionHandler::ATTR_RESPONDER, [$responder, $params]);
    }

    public function handleError(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request->withAttribute(
            ActionHandler::ATTR_RESPONDER,
            [ErrorResponder::class, [':message' => 'Invalid registration request-data.']]
        );
    }

    public function registerValidator(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request->withAttribute(
            ActionHandler::ATTR_VALIDATOR,
            [RegistrationValidator::class, [':exportTo' => self::ATTR_INFOS]]
        );
    }

    public function isSecure(): bool
    {
        return false;
    }
}
