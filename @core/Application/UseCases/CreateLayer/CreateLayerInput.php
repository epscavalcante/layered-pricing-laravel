<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateLayer;

readonly class CreateLayerInput
{
    public function __construct(
        public string $code,
        public string $type,
        public ?string $layerId = null,
        public ?string $discountType = null,
        public ?int $discountValue = null,
    ) {}
}
