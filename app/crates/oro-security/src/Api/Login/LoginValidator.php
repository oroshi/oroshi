<?php

declare(strict_types=1);

namespace Oro\Security\Api\Login;

use Oroshi\Core\Middleware\Action\ValidatorInterface;
use Oroshi\Core\Middleware\Action\ValidatorTrait;
use Oroshi\Core\Middleware\ActionHandler;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Stringy\Stringy;

final class LoginValidator implements ValidatorInterface
{
    use ValidatorTrait;

    /** @var int */
    private const PWD_MIN = 8;

    /** @var int */
    private const PWD_MAX = 60;

    /** @var int */
    private const NAME_MIN = 1;

    /** @var int */
    private const NAME_MAX = 30;

    /** @var string[] */
    private const INPUT_FIELDS = ['username', 'password'];

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
        $userInfos = $this->validateFields(self::INPUT_FIELDS, $request, $errors = []);
        return empty($errors)
            ? $request->withAttribute($this->exportTo, $userInfos)
            : $request->withAttribute($this->errExport, $errors);
    }

    private function validateUsername($username, array &$errors): ?string
    {
        if (!is_string($username)) {
            $errors[] = 'Username must be a string.';
            return null;
        }
        if ($lengthErr = $this->checkLength('username', $username, self::NAME_MIN, self::NAME_MAX)) {
            $errors[] = $lengthErr;
            return null;
        }
        return $username;
    }

    private function validatePassword($password, array &$errors): ?string
    {
        if (!is_string($password)) {
            $errors[] = 'Password must be a string.';
            return null;
        }
        if ($lengthErr = $this->checkLength('password', $password, self::PWD_MIN, self::PWD_MAX)) {
            $errors[] = $lengthErr;
            return null;
        }
        return $password;
    }

    private function checkLength(string $fieldname, string $value, int $min, int $max): ?string
    {
        $length = mb_strlen($value);
        if ($length < $min || $length > $max) {
            return sprintf(
                '%s must be at least %d and the most %d chars long.',
                ucfirst($fieldname),
                self::NAME_MIN,
                self::NAME_MAX
            );
        }
        return null;
    }
}
