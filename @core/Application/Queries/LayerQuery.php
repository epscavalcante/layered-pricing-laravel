<?php

declare(strict_types=1);

namespace Src\Application\Queries;

interface LayerQuery
{
    public function list(ListQueryInput $input): ListQueryOutput;
}

class ListQueryInput
{
    public function __construct(
        public ?string $sortDirection = null,
        public ?string $sortBy = null,
        public ?int $page = null,
        public ?int $perPage = null,
    ) {}
}

class ListQueryOutput
{
    /**
     * @param  ListQueryItemOutput[]  $items
     */
    public function __construct(
        public int $total,
        public array $items,
    ) {}
}

class ListQueryItemOutput
{
    public function __construct(
        public string $code,
        public string $type,
        public ?string $layerId = null,
        public ?string $discountType = null,
        public ?string $discountValue = null,
    ) {}
}
