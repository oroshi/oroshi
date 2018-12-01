<?php

declare(strict_types=1);

namespace Oroshi\Middleware;

use Psr\Http\Server\RequestHandlerInterface;

interface PipelineBuilderInterface
{
    public function __invoke(): RequestHandlerInterface;
}
