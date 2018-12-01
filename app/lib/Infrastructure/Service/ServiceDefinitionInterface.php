<?php

declare(strict_types=1);

namespace Oroshi\Infrastructure\Service;

interface ServiceDefinitionInterface
{
    public function getServiceClass(): string;

    public function getProvisionerClass(): string;

    public function getSettings(): array;

    public function getSubscriptions(): array;
}
