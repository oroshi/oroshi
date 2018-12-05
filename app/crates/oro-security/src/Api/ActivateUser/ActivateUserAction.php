<?php

declare(strict_types=1);

namespace Oro\Security\Api\ActivateUser;

use Oro\Security\Api\ErrorResponder;
use Oro\Security\Api\UserActionTrait;
use Oroshi\Core\Middleware\Action\ActionInterface;
use Oroshi\Core\Middleware\ActionHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ActivateUserAction implements ActionInterface
{
    use UserActionTrait;

    const ATTR_USER = 'user';

    public function __invoke(ServerRequestInterface $request): ServerRequestInterface
    {
        try {
            $user = $request->getAttribute(self::ATTR_USER);
            $this->userService->activate($user);

            $responder = ActivationResponder::class;
            $params = [':user' => $user];
        } catch (\Exception $error) {
            $errMsg = 'Unexpected error occured during activation.';
            $this->logger->error($errMsg, ['exception' => $error]);

            $responder = ErrorResponder::class;
            $params = [':message' => $errMsg];
        }
        return $request->withAttribute(ActionHandler::ATTR_RESPONDER, [$responder, $params]);
    }

    public function handleError(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request->withAttribute(
            ActionHandler::ATTR_RESPONDER,
            [ErrorResponder::class, [':message' => 'Invalid activation request-data.']]
        );
    }

    public function registerValidator(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request->withAttribute(
            ActionHandler::ATTR_VALIDATOR,
            [ActivationValidator::class, [':exportTo' => self::ATTR_USER]]
        );
    }

    public function isSecure(): bool
    {
        return false;
    }
}
