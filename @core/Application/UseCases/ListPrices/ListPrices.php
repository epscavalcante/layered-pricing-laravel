<?php

declare(strict_types=1);

namespace Src\Application\UseCases\ListPrices;

use Src\Application\Queries\ListPriceQuery\ListPriceQuery;

class ListPrices
{
    public function __construct(
        private readonly ListPriceQuery $listPriceQuery,
    ) {}

    public function execute(ListPricesInput $input): ListPricesOutput
    {
        $output = $this->listPriceQuery->query($input);

        return new ListPricesOutput(
            total: $output->total,
            items: $output->items,
        );
    }
}
