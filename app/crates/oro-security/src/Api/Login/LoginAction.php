<?php

declare(strict_types=1);

namespace Oro\Security\Api\Login;

use function GuzzleHttp\Psr7\parse_query;
use Oro\Security\Api\ErrorResponder;
use Oro\Security\Api\Logout\LoginResponder;
use Oro\Security\Api\UserActionTrait;
use Oro\Security\ReadModel\Standard\User;
use Oroshi\Core\Middleware\Action\ActionInterface;
use Oroshi\Core\Middleware\ActionHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class LoginAction implements ActionInterface
{
    use UserActionTrait;

    const ATTR_LOGIN = 'login';

    public function __invoke(ServerRequestInterface $request): ServerRequestInterface
    {
        $login = $request->getAttribute(self::ATTR_LOGIN);
        try {
            if ($user = $this->userService->authenticate(...$login)) {
                $responder = LoginResponder::class;
                $params = [':user' => $user, ':token' => $this->userService->generateJWT($user)];
            } else {
                $responder = ErrorResponder::class;
                $params = [':message' => 'Failed to login user.'];
            }
        } catch (\Exception $error) {
            $errMsg = 'Unexpected error occured during login.';
            $responder = ErrorResponder::class;
            $params = [':message' => $errMsg];
            $this->logger->error($errMsg, ['exception' => $error->getMessage()]);
        }
        return $request->withAttribute(ActionHandler::ATTR_RESPONDER, [$responder, $params]);
    }

    public function handleError(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request->withAttribute(
            ActionHandler::ATTR_RESPONDER,
            [ErrorResponder::class, [':message' => 'Invalid login request-data.']]
        );
    }

    public function registerValidator(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request->withAttribute(
            ActionHandler::ATTR_VALIDATOR,
            [LoginValidator::class, [':exportTo' => self::ATTR_LOGIN]]
        );
    }

    public function isSecure(): bool
    {
        return false;
    }
}
