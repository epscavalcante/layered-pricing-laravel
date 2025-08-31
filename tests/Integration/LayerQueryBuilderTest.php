<?php

use App\Models\Layer;
use App\Queries\LayerQueryBuilder;
use Src\Domain\Enums\DiscountType;
use Src\Application\Queries\ListQueryInput;
use Src\Application\Queries\ListQueryOutput;

it('Deve listar retornar uma lista e layers', function () {
    $baseLayersCount = rand(1, 5);
    $discountLayersCount = rand(1, 5);
    $baseLayers = Layer::factory()->normal()->count($baseLayersCount)->create();
    $baseLayers->each(function (Layer $layer) use ($discountLayersCount) {
        $discountTypes = DiscountType::cases();
        $discountType = $discountTypes[array_rand($discountTypes)];
        Layer::factory()
            ->for($layer, 'parent')
            ->discountable(
                $discountType,
                rand(1, 100)
            )->count($discountLayersCount)
            ->create();
    });

    $layerQuery = new LayerQueryBuilder;
    $layerQueryInput = new ListQueryInput(
        sortDirection: 'DESC',
        sortBy: 'id',
        page: 1,
        perPage: 7
    );
    $layerQueryResult = $layerQuery->list($layerQueryInput);

    expect($layerQueryResult)->toBeInstanceOf(ListQueryOutput::class);
    expect($layerQueryResult->total)->toBe(($baseLayersCount * $discountLayersCount) + $baseLayersCount);
    expect($layerQueryResult->items)->toBeArray();
});
