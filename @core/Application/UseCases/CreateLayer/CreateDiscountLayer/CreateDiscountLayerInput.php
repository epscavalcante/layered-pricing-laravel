<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateLayer\CreateDiscountLayer;

readonly class CreateDiscountLayerInput
{
    public function __construct(
        public string $layerId,
        public string $code,
        public string $type,
        public int $value,
    ) {}
}
