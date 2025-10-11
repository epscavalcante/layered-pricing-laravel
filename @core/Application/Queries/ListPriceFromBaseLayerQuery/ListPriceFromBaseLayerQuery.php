<?php

declare(strict_types=1);

namespace Src\Application\Queries\ListPriceFromBaseLayerQuery;

interface ListPriceFromBaseLayerQuery
{
    public function query(ListPriceFromBaseLayerQueryInput $input): ListPriceFromBaseLayerQueryOutput;
}
