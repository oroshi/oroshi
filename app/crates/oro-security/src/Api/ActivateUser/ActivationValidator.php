<?php

declare(strict_types=1);

namespace Oro\Security\Api\ActivateUser;

use Oro\Security\ReadModel\Standard\User;
use Oro\Security\ReadModel\Standard\Users;
use Oro\Security\ValueObject\RandomToken;
use Oroshi\Core\Middleware\Action\ValidatorInterface;
use Oroshi\Core\Middleware\Action\ValidatorTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

final class ActivationValidator implements ValidatorInterface
{
    use ValidatorTrait;

    /** @var string */
    private const TOKEN = '_vt';

    /** @var LoggerInterface */
    private $logger;

    /** @var Users */
    private $users;

    /** @var string */
    private $exportTo;

    /** @var string */
    private $errExport;

    public function __construct(
        LoggerInterface $logger,
        Users $users,
        string $exportTo,
        string $errExport = 'errors'
    ) {
        $this->logger = $logger;
        $this->exportTo = $exportTo;
        $this->errExport = $errExport;
        $this->users = $users;
    }

    public function __invoke(ServerRequestInterface $request): ServerRequestInterface
    {
        $user = null;
        $inputData = $this->validateFields([self::TOKEN], $request, $errors = []);
        if (empty($errors)) {
            if (!$user = $this->users->byToken($inputData[self::TOKEN])) {
                $errors[] = 'Given token is invalid or has expired.';
                return null;
            }
        }
        return !is_null($user)
            ? $request->withAttribute($this->exportTo, $user)
            : $request->withAttribute($this->errExport, $errors);
    }

    private function validateToken($token, array &$output, array &$errors): ?User
    {
        if (!is_string($token)) {
            $errors[] = 'Token must be a string.';
            return null;
        }
        return $token;
    }
}
