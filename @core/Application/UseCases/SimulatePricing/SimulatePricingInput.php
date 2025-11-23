<?php

declare(strict_types=1);

namespace Src\Application\UseCases\SimulatePricing;

readonly class SimulatePricingInput
{
    public function __construct(
        public readonly string $baseLayerId,
        public readonly string $operation, // Discount or Increase
        public readonly string $operationType, // Percentage or Fixed
        public readonly int $operationValue
    ) {}
}
