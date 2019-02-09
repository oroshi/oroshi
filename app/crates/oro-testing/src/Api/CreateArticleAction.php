<?php

declare(strict_types=1);

namespace Oro\Testing\Api;

use Daikon\Config\ConfigProviderInterface;
use Daikon\Entity\ValueObject\Uuid;
use Daikon\MessageBus\MessageBusInterface;
use Oro\Testing\Article\Create\CreateArticle;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class CreateArticleAction
{
    private $config;

    private $messageBus;

    public function __construct(ConfigProviderInterface $config, MessageBusInterface $messageBus)
    {
        $this->config = $config;
        $this->messageBus = $messageBus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $createArticle = CreateArticle::fromNative([
            'aggregateId' => 'oro.testing.article-8f69a4f0-c681-440d-85a3-0ba62dafb1e2',
            'title' => 'hello world!',
            'content' => 'this is my first article! amazing!'
        ]);
        if ($this->messageBus->publish($createArticle, 'commands')) {
            return new JsonResponse([
                'status' => 'yay',
                'msg' => 'successfully created article',
                'version' => $this->config->get('app.version'),
                'environment' => $this->config->get('app.env')
            ]);
        }
        return new JsonResponse([
            'status' => 'noes',
            'version' => $this->config->get('app.version'),
            'environment' => $this->config->get('app.env')
        ]);
    }
}
