<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;
use Src\Application\Queries\ListPriceQuery\ListPriceQuery;
use Src\Application\Queries\ListPriceQuery\ListPriceQueryInput;
use Src\Application\Queries\ListPriceQuery\ListPriceQueryItemOutput;
use Src\Application\Queries\ListPriceQuery\ListPriceQueryOutput;

class ListPriceQueryBuilder implements ListPriceQuery
{
    public function query(ListPriceQueryInput $input): ListPriceQueryOutput
    {
        $query = DB::table('prices');

        if (!empty($input->layerIds)) {
            $query->whereIn(
                column: 'layer_id',
                values: $input->layerIds
            );
        }

        if (!empty($input->productIds)) {
            $query->whereIn(
                column: 'product_id',
                values: $input->productIds
            );
        }

        if ($input->sortBy && $input->sortDirection) {
            $query->orderBy(
                column: $input->sortBy,
                direction: $input->sortDirection
            );
        }

        $hasPagination = $input->page && $input->perPage;

        $result = $hasPagination
            ? $query->paginate(perPage: $input->perPage, page: $input->page)
            : $query->get();

        $resultTotal = $hasPagination
            ? $result->total()
            : $result->count();

        $resultItems = $hasPagination
            ? $result->items()
            : $result->all();

        return new ListPriceQueryOutput(
            total: $resultTotal,
            items: array_map(
                callback: fn(object $item) => $this->buildItemList($item),
                array: $resultItems
            ),
        );
    }

    private function buildItemList(object $item): ListPriceQueryItemOutput
    {
        return new ListPriceQueryItemOutput(
            priceId: $item->id,
            layerId: $item->layer_id,
            productId: $item->product_id,
            value: $item->value,
        );
    }
}
