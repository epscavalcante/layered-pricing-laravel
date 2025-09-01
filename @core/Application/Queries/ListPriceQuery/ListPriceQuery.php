<?php

declare(strict_types=1);

namespace Src\Application\Queries\ListPriceQuery;

interface ListPriceQuery
{
    public function query(ListPriceQueryInput $input): ListPriceQueryOutput;
}
