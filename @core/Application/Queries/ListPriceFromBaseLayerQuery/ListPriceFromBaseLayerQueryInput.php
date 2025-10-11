<?php

declare(strict_types=1);

namespace Src\Application\Queries\ListPriceFromBaseLayerQuery;

readonly class ListPriceFromBaseLayerQueryInput
{
    public function __construct(
        public string $layerId,
        public ?array $productIds = null,
        public ?string $sortDirection = null,
        public ?string $sortBy = null,
        public ?int $page = null,
        public ?int $perPage = null,
    ) {}
}
