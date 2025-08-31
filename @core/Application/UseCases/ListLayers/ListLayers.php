<?php

declare(strict_types=1);

namespace Src\Application\UseCases\ListLayers;

use Src\Application\Queries\LayerQuery;
use Src\Application\Queries\ListQueryInput;

class ListLayers
{
    public function __construct(
        private readonly LayerQuery $layerQuery,
    ) {}

    public function execute(ListLayersInput $input): ListLayersOutput
    {
        $layerQueryInput = new ListQueryInput(
            sortDirection: $input->sortDirection,
            sortBy: $input->sortBy,
            page: $input->page,
            perPage: $input->perPage
        );
        $layerQueryOutput = $this->layerQuery->list($layerQueryInput);
        return new ListLayersOutput(
            total: $layerQueryOutput->total,
            items: $layerQueryOutput->items,
        );
    }
}
