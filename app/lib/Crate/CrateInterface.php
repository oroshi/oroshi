<?php

declare(strict_types=1);

namespace Oroshi\Crate;

interface CrateInterface
{
    public function getLocation(): string;

    public function getSettings(): array;
}
