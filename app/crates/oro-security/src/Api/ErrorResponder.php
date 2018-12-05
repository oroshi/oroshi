<?php

declare(strict_types=1);

namespace Oro\Security\Api;

use Oroshi\Core\Middleware\Action\ResponderInterface;
use Oroshi\Core\Middleware\Action\ResponderTrait;
use Oroshi\Core\Middleware\ActionHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class ErrorResponder implements ResponderInterface
{
    use ResponderTrait;

    /** @var string */
    private $message;

    /** @var int */
    private $statusCode;

    public function __construct(string $message, int $statusCode = 200)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    public function respondToJson(ServerRequestInterface $request): ResponseInterface
    {
        $errors = $request->getAttribute(ActionHandler::ATTR_ERRORS, []);
        return new JsonResponse(['message' => $this->message, 'errors' => $errors], $this->statusCode);
    }

    public function respondToHtml(ServerRequestInterface $request): ResponseInterface
    {
        return $this->respondToJson($request);
    }
}
