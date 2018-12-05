<?php

declare(strict_types=1);

namespace Oro\Security;

use Assert\Assertion;
use Daikon\Config\ConfigProviderInterface;
use Daikon\Entity\ValueObject\Timestamp;
use Daikon\Entity\ValueObject\Uuid;
use Daikon\EventSourcing\Aggregate\Command\CommandInterface;
use Daikon\MessageBus\MessageBusInterface;
use Firebase\JWT\JWT;
use Oro\Security\ReadModel\Standard\User;
use Oro\Security\ReadModel\Standard\Users;
use Oro\Security\User\Activate\ActivateUser;
use Oro\Security\User\Register\RegisterUser;
use Oro\Security\ValueObject\PasswordHash;
use Oro\Security\ValueObject\RandomToken;
use Oro\Security\ValueObject\UserRole;
use RuntimeException;

final class UserService
{
    const CHAN_COMMANDS = 'commands';

    /** @var ConfigProviderInterface */
    private $config;

     /** @var MessageBusInterface */
    private $msgBus;

    /** @var Users */
    private $users;

    public function __construct(ConfigProviderInterface $config, MessageBusInterface $msgBus, Users $users)
    {
        $this->config = $config;
        $this->msgBus = $msgBus;
        $this->users = $users;
    }

    public function register(array $userInfos, UserRole $role = null): RegisterUser
    {
        Assertion::keyIsset($userInfos, 'passwordHash');
        $userInfos = array_merge($userInfos, [
            'role' => (string)($role ?? 'user'),
            'aggregateId' => 'oro.security.user-'.Uuid::generate(),
            'passwordHash' => (string)PasswordHash::gen($userInfos['passwordHash']),
            'authTokenExpiresAt' => gmdate(Timestamp::NATIVE_FORMAT, strtotime('+1 month'))
        ]);
        $userRegistration = RegisterUser::fromNative($userInfos);
        $this->dispatch($userRegistration);
        return $userRegistration;
    }

    public function activate(User $user): void
    {
        $activateUser = ActivateUser::fromNative([
            'aggregateId' => (string)$user->getAggregateId()
        ]);
        $this->dispatch($activateUser);
    }

    public function authenticate(string $username, string $password): ?User
    {
        if (!$user = $this->users->byUsername($username)) {
            return null;
        }
        if (!$hash = $user->getPasswordHash()) {
            return null;
        }
        return $hash->verify($password) ? $user : null;
    }

    public function generateJWT(User $user): string
    {
        $secretKey = $this->config->get('jwt.secret', 'foobar');
        return JWT::encode([
            'iss' => $this->config->get('project.name'),
            'aud' => $this->config->get('project.name'),
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 60 * 60 * 24, // 1 day expiry period
            'data' => [
                'id' => (string)$user->getAggregateId(),
                'username' => (string)$user->getUsername(),
                'role' => (string)$user->getRole(),
                'state' => (string)$user->getState()
            ]
        ], $secretKey);
    }

    private function dispatch(CommandInterface $command): void
    {
        if (!$this->msgBus->publish($command, self::CHAN_COMMANDS)) {
            throw new RuntimeException(get_class($command).' was not handled by msg-bus.');
        }
    }
}
