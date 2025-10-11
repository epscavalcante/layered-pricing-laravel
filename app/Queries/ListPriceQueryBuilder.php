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
        $query = DB::table('prices')
            ->select([
                'prices.id as price_id',
                'layers.id as layer_id',
                'products.id as product_id',
                'products.name as product_name',
                'layers.code as layer_code',
                'layers.type as layer_type',
                'prices.value as value',
            ])
            ->join(
                table: 'products',
                first: 'products.id',
                operator: '=',
                second: 'prices.product_id'
            )
            ->join(
                table: 'layers',
                first: 'layers.id',
                operator: '=',
                second: 'prices.layer_id'
            );

        if (! empty($input->layerIds)) {
            $query->whereIn(
                column: 'prices.layer_id',
                values: $input->layerIds
            );
        }

        if (! empty($input->productIds)) {
            $query->whereIn(
                column: 'prices.product_id',
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
                callback: fn (object $item) => $this->buildItemList($item),
                array: $resultItems
            ),
        );
    }

    /*
    $query = DB::table('products as p')
            ->select(
                'p.id as product_id',
                'p.name as product_name',
                DB::raw('COALESCE(price_discount.id, price_base.id) AS final_price_id'),
                DB::raw('COALESCE(pl_discount.price, pl_base.price) as final_price'),
                'pl_discount.price as discount_price',
                'pl_base.price as base_price',
                "CASE
                    WHEN pl_discount.id IS NOT NULL THEN 'discount'
                    ELSE 'base'
                END AS price_type",
            )
            ->leftJoin('prices as pl_discount', function ($join) use ($layerId) {
                $join->on('pl_discount.product_id', '=', 'p.id')
                    ->where('pl_discount.layer_id', '=', $layerId);
            })
            ->leftJoin('layers as l', 'l.id', '=', DB::raw($layerId))
            ->leftJoin('prices as pl_base', function ($join) {
                $join->on('pl_base.product_id', '=', 'p.id')
                    ->on('pl_base.layer_id', '=', 'l.parent_id');
            });
            */

    private function buildItemList(object $item): ListPriceQueryItemOutput
    {
        return new ListPriceQueryItemOutput(
            priceId: $item->price_id,
            layerId: $item->layer_id,
            productId: $item->product_id,
            productName: $item->product_name,
            layerCode: $item->layer_code,
            layerType: $item->layer_type,
            value: $item->value,
        );
    }
}
