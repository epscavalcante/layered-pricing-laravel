<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;
use Src\Application\Queries\LayerQuery;
use Src\Application\Queries\ListQueryInput;
use Src\Application\Queries\ListQueryItemOutput;
use Src\Application\Queries\ListQueryOutput;

class LayerQueryBuilder implements LayerQuery
{
    public function list(ListQueryInput $input): ListQueryOutput
    {
        $query = DB::table('layers');

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

        $total = $hasPagination
            ? $result->total()
            : $result->count();

        $items = $hasPagination
            ? array_map(
                callback: fn(object $item) => new ListQueryItemOutput(
                    layerId: $item->parent_id,
                    code: $item->code,
                    type: $item->type,
                    discountType: $item->discount_type,
                    discountValue: $item->discount_value,
                ),
                array: $result->items()
            )
            : array_map(
                callback: fn(object $item) => new ListQueryItemOutput(
                    layerId: $item->parent_id,
                    code: $item->code,
                    type: $item->type,
                    discountType: $item->discount_type,
                    discountValue: $item->discount_value,
                ),
                array: $result->all()
            );

        return new ListQueryOutput(
            total: $total,
            items: $items,
        );
    }
}
