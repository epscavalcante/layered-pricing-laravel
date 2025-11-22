<?php

declare(strict_types=1);

namespace Src\Application\UseCases\SimulatePricing;

readonly class SimulatePricingOutput
{
    public function __construct(public readonly array $items) {}
}
