<?php

declare(strict_types=1);

namespace Oro\Security\Api\RegisterUser;

use Oro\Security\Api\ValidationTrait;
use Oroshi\Core\Middleware\ValidationInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

final class RegistrationValidator implements ValidationInterface
{
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
        $data = $this->getFields($input, $errors, ['username', 'email', 'passwordHash']);
        $userInfos = $this->validateFields($data, $errors);
        if (empty($errors)) {
            return $request->withAttribute($this->attribute, $userInfos);
        }
        return $request->withAttribute('errors', $errors);
    }

    private function validateUsername($username, array &$errors): ?string
    {
        return $username;
    }

    private function validateEmail($email, array &$errors): ?string
    {
        return $email;
    }

    private function validatePasswordHash($passwordHash, array &$errors): ?string
    {
        return $passwordHash;
    }
}
