<?php

declare(strict_types=1);

namespace Src\Application\Queries\ListPriceQuery;

readonly class ListPriceQueryInput
{
    public function __construct(
        public ?array $layerIds = null,
        public ?array $productIds = null,
        public ?string $sortDirection = null,
        public ?string $sortBy = null,
        public ?int $page = null,
        public ?int $perPage = null,
    ) {}
}
