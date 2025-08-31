<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateLayer\CreateSimpleLayer;

readonly class CreateSimpleLayerInput
{
    public function __construct(
        public string $code,
    ) {}
}
