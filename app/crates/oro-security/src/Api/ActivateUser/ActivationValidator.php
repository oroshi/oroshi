<?php

declare(strict_types=1);

namespace Oro\Security\Api\ActivateUser;

use Oro\Security\ValueObject\RandomToken;
use Oroshi\Core\Middleware\Action\ValidatorInterface;
use Oroshi\Core\Middleware\Action\ValidatorTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

final class ActivationValidator implements ValidatorInterface
{
    use ValidatorTrait;

    /** @var string */
    private const INPUT_FIELD = '_vt';

    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $exportTo;

    /** @var string */
    private $errExport;

    public function __construct(LoggerInterface $logger, string $exportTo, string $errExport = 'errors')
    {
        $this->logger = $logger;
        $this->exportTo = $exportTo;
        $this->errExport = $errExport;
    }

    public function __invoke(ServerRequestInterface $request): ServerRequestInterface
    {
        $tokenInfo = $this->validateFields([self::INPUT_FIELD], $request, $errors = []);
        return empty($errors)
            ? $request->withAttribute($this->exportTo, $tokenInfo[self::INPUT_FIELD])
            : $request->withAttribute($this->errExport, $errors);
    }

    private function validateToken($token, array &$errors): ?string
    {
        return $token;
    }
}
