<?php

declare(strict_types=1);

namespace Oro\Security\Api;

use Assert\Assertion;
use Oro\Security\UserService;
use Oroshi\Core\Middleware\ValidationInterface;
use Oroshi\Core\Service\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\Response\JsonResponse;

trait UserActionTrait
{
    /** @var LoggerInterface */
    private $logger;

    /** @var Container */
    private $container;

    /** @var UserService */
    private $userService;

    public function __construct(
        LoggerInterface $logger,
        Container $container,
        UserService $userService
    ) {
        $this->logger = $logger;
        $this->container = $container;
        $this->userService = $userService;
    }

    public function isSecure(): bool
    {
        return true;
    }

    public function getValidation(): ?ValidationInterface
    {
        return null;
    }

    private function makeValidation(string $fqcn, array $state): ValidationInterface
    {
        Assertion::classExists($fqcn);
        $validation = $this->container->make($fqcn, $state);
        Assertion::isInstanceOf($validation, ValidationInterface::class);
        return $validation;
    }

    private function hasError(ServerRequestInterface $request): bool
    {
        return !empty($request->getAttribute('errors', []));
    }

    private function errorResponse(string $msg, ServerRequestInterface $request): ResponseInterface
    {
        $validationErrors = $request->getAttribute('errors', []);
        return new JsonResponse(['message' => $msg, 'errors' => $validationErrors]);
    }
}
