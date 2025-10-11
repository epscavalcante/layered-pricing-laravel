<?php

declare(strict_types=1);

namespace Src\Application\Queries\ListPriceFromBaseLayerQuery;

readonly class ListPriceFromBaseLayerQueryOutput
{
    /**
     * @param  ListQueryItemOutput[]  $items
     */
    public function __construct(
        public int $total,
        public array $items,
    ) {}
}

class ListPriceFromBaseLayerQueryItemOutput
{
    public function __construct(
        public string $priceId,
        public string $productId,
        public string $productName,
        // discountValue
        public int $basePrice,
        public int $parentPrice, // pode ser reajuste
        public int $finalPrice,
    ) {}
}
