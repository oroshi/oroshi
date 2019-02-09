<?php

declare(strict_types=1);

namespace Oro\Testing\Api;

use Daikon\Config\ConfigProviderInterface;
use Daikon\Entity\ValueObject\Uuid;
use Daikon\MessageBus\MessageBusInterface;
use Oro\Testing\Article\Update\UpdateArticle;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class UpdateArticleAction
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
        $updateArticle = UpdateArticle::fromNative([
            'aggregateId' => 'oro.testing.article-8f69a4f0-c681-440d-85a3-0ba62dafb1e2',
            'knownAggregateRevision' => 2,
            'title' => 'updated! again!!!!',
            'content' => 'updated again, dude omg!!!!!'
        ]);
        if ($this->messageBus->publish($updateArticle, 'commands')) {
            return new JsonResponse([
                'status' => 'yay',
                'msg' => 'successfully update article',
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
