<?php

declare(strict_types=1);

namespace Src\Application\UseCases\ListLayers;

readonly class ListLayersInput
{
    public function __construct(
        public ?int $page = 1,
        public ?int $perPage = 10,
        public ?string $sortBy = null,
        public ?string $sortDirection = null,
    ) {}
}
