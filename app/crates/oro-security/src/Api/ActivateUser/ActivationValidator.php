<?php

declare(strict_types=1);

namespace Oro\Security\Api\ActivateUser;

use Oro\Security\ValueObject\RandomToken;
use Oroshi\Core\Middleware\ValidationInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

final class ActivationValidator implements ValidationInterface
{
    const PARAM_TOKEN = '_vt';

    use ValidationTrait;

    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $attribute;

    public function __construct(LoggerInterface $logger, string $attribute)
    {
        $this->logger = $logger;
        $this->attribute = $attribute;
    }

    public function __invoke(ServerRequestInterface $request): ServerRequestInterface
    {
        $errors = [];
        $input = $this->getInput($request);
        $data = $this->getFields($input, $errors, [self::PARAM_TOKEN]);
        $tokenData = $this->validateFields($data, $errors);
        if (empty($errors)) {
            return $request->withAttribute($this->attribute, $tokenData[self::PARAM_TOKEN]);
        }
        return $request->withAttribute('errors', $errors);
    }

    private function validateToken($token, array &$errors): ?string
    {
        return $token;
    }
}
