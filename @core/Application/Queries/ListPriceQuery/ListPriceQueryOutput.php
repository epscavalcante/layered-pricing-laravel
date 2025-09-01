<?php

declare(strict_types=1);

namespace Src\Application\Queries\ListPriceQuery;

readonly class ListPriceQueryOutput
{

    /**
     * @param int $total
     * @param ListQueryItemOutput[] $items
     */
    public function __construct(
        public int $total,
        public array $items,
    ) {}
}

class ListPriceQueryItemOutput
{
    public function __construct(
        public string $priceId,
        public string $layerId,
        public string $productId,
        public int $value,
    ) {}
}
