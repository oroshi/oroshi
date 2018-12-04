<?php

declare(strict_types=1);

namespace Oro\Security\Api\RegisterUser;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Oro\Security\Api\ValidationTrait;
use Oroshi\Core\Middleware\ValidationInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Stringy\Stringy;

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
        $success = true;
        if (!is_string($username)) {
            $errors[] = 'Username must be a string.';
            return null;
        }
        $length = mb_strlen($username);
        if ($length < 3 || $length > 30) {
            $errors[] = 'Username must be at least 3 and the most 30 chars long.';
            return null;
        }
        $username = trim($username);
        return $username;
    }

    private function validateEmail($email, array &$errors): ?string
    {
        if (!is_string($email)) {
            $errors[] = 'Email must be a string.';
            return null;
        }
        if (!(new EmailValidator)->isValid($email, new RFCValidation)) {
            $errors[] = 'Invalid email format given.';
            return null;
        }
        return $email;
    }

    private function validatePasswordHash($passwordHash, array &$errors): ?string
    {
        if (!is_string($passwordHash)) {
            $errors[] = 'PasswordHash must be a string.';
            return null;
        }
        $length = mb_strlen($username);
        if ($length < 3 || $length > 30) {
            $errors[] = 'Password must be at least 3 and the most 30 chars long.';
            return null;
        }
        return $passwordHash;
    }
}
