<?php

declare(strict_types=1);

namespace Src\Application\Queries\ListPriceFromBaseLayerQuery;

interface ListPriceQuery
{
    public function query(ListPriceFromBaseLayerQueryInput $input): ListPriceFromBaseLayerQueryOutput;
}
