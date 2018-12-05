<?php

declare(strict_types=1);

namespace Oro\Security\Api\ActivateUser;

use function GuzzleHttp\Psr7\parse_query;
use Oro\Security\Api\UserActionTrait;
use Oro\Security\ReadModel\Standard\User;
use Oro\Security\ValueObject\RandomToken;
use Oroshi\Core\Middleware\Action\ActionInterface;
use Oroshi\Core\Middleware\ActionHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class ActivateUserAction implements ActionInterface
{
    use UserActionTrait;

    const ATTR_USER = 'user';

    public function __invoke(ServerRequestInterface $request): ServerRequestInterface
    {
        try {
            $user = $request->getAttribute(self::ATTR_USER);
            $this->userService->activate($user);

            $responder = SuccessResponder::class;
            $params = [':user' => $user];
        } catch (\Exception $error) {
            $errMsg = 'Unexpected error occured during activation.';
            $this->logger->error($errMsg, ['exception' => $error]);

            $responder = ErrorResponder::class;
            $params = [':message' => $errMsg];
        }
        return $request->withAttribute(ActionHandler::ATTR_RESPONDER, [$responder, $params]);
    }

    public function handleError(ServerRequestInterface $request): ResponseInterface
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
