<?php

declare(strict_types=1);

namespace Oro\Security\Api\RegisterUser;

use Oro\Security\User\Register\RegisterUser;
use Oroshi\Core\Middleware\Action\ResponderInterface;
use Oroshi\Core\Middleware\Action\ResponderTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class RegistrationResponder implements ResponderInterface
{
    use ResponderTrait;

    /** @var RegisterUser */
    private $registration;

    public function __construct(RegisterUser $registration)
    {
        $this->registration = $registration;
    }

    public function respondToJson(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['message' => 'Successfully registered user.']);
    }

    public function respondToHtml(ServerRequestInterface $request): ResponseInterface
    {
        return $this->respondToJson($request);
    }
}
