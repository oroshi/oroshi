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

final class UserService
{
    const CHAN_COMMANDS = 'commands';

    /** @var ConfigProviderInterface */
    private $configProvider;

     /** @var MessageBusInterface */
    private $msgBus;

    /** @var Users */
    private $users;

    public function __construct(
        ConfigProviderInterface $configProvider,
        MessageBusInterface $msgBus,
        Users $users
    ) {
        $this->configProvider = $configProvider;
        $this->msgBus = $msgBus;
        $this->users = $users;
    }

    public function register(array $userInfos, UserRole $role = null): ?RegisterUser
    {
        Assertion::keyExists($userInfos, 'passwordHash');
        if (is_string($userInfos['passwordHash'])) {
            $userInfos['passwordHash'] = (string)PasswordHash::gen($userInfos['passwordHash']);
        }
        if (!$role) {
            $userInfos['role'] = $role ? (string)$role : 'user';
        }
        $userInfos['aggregateId'] = 'oro.security.user-'.Uuid::generate();
        $userInfos['authTokenExpiresAt'] = gmdate(Timestamp::NATIVE_FORMAT, strtotime('+1 month'));
        $registerUser = RegisterUser::fromNative($userInfos);
        return $this->dispatch($registerUser) ? $registerUser : null;
    }

    public function activate(RandomToken $token): ?User
    {
        if (!$user = $this->users->byToken($token)) {
            return null;
        }
        $activateUser = ActivateUser::fromNative([
            'aggregateId' => (string)$user->getAggregateId()
        ]);
        return $this->dispatch($activateUser) ? $user : null;
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
        $secretKey = $this->configProvider->get('jwt.secret', 'foobar');
        return JWT::encode([
            'iss' => $this->configProvider->get('project.name'),
            'aud' => $this->configProvider->get('project.name'),
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

    private function dispatch(CommandInterface $command): bool
    {
        return $this->msgBus->publish($command, self::CHAN_COMMANDS);
    }
}
