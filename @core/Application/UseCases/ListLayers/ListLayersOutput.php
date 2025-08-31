<?php

declare(strict_types=1);

namespace Src\Application\UseCases\ListLayers;

readonly class ListLayersOutput
{
    public function __construct(
        public int $total,
        public array $items,
    ) {}
}
