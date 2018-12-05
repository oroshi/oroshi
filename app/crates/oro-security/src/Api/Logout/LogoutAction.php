<?php

declare(strict_types=1);

namespace Oro\Security\Api\Logout;

use Oro\Security\Api\ErrorResponder;
use Oro\Security\Api\UserActionTrait;
use Oroshi\Core\Middleware\Action\ActionInterface;
use Oroshi\Core\Middleware\ActionHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class LogoutAction implements ActionInterface
{
    use UserActionTrait;

    public function __invoke(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request->withAttribute(
            ActionHandler::ATTR_RESPONDER,
            function (ServerRequestInterface $request): ResponseInterface {
                return new JsonResponse(['message' => 'Successfully logged out.']);
            }
        );
    }

    public function handleError(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request->withAttribute(
            ActionHandler::ATTR_RESPONDER,
            [ErrorResponder::class, [':message' => 'Invalid logout request-data.']]
        );
    }
}
