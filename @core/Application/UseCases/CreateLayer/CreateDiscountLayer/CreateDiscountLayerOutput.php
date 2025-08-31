<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateLayer\CreateDiscountLayer;

readonly class CreateDiscountLayerOutput
{
    public function __construct(
        public string $layerId,
    ) {}
}
