<?php

declare(strict_types=1);

namespace Oro\Security\Api;

use Oro\Security\UserService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

trait UserActionTrait
{
    /** @var LoggerInterface */
    private $logger;

    /** @var UserService */
    private $userService;

    public function __construct(LoggerInterface $logger, UserService $userService)
    {
        $this->logger = $logger;
        $this->userService = $userService;
    }

    public function isSecure(): bool
    {
        return true;
    }

    public function registerValidator(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request;
    }
}
