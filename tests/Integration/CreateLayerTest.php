<?php

use App\Repositories\LayerModelRepository;
use Src\Application\UseCases\CreateLayer\CreateLayer;
use Src\Application\UseCases\CreateLayer\CreateLayerInput;
use Src\Application\UseCases\CreateLayer\CreateLayerOutput;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;

it('Deve criar uma layer normal', function () {
    $input = new CreateLayerInput(
        type: LayerType::NORMAL->value,
        code: uniqid('LAYER_', true),
    );
    $useCase = new CreateLayer(
        layerRepository: new LayerModelRepository
    );
    $output = $useCase->execute($input);
    expect($output)->toBeInstanceOf(CreateLayerOutput::class);
    expect($output->layerId)->toBeString();
});

it('Deve criar uma layer de desconto', function () {
    $layerRepository = new LayerModelRepository;
    $baseLayerInput = new CreateLayerInput(type: LayerType::NORMAL->value, code: uniqid('BASE_LAYER_', true));
    $baseLayerUseCase = new CreateLayer(layerRepository: $layerRepository);
    $baseLayerOutput = $baseLayerUseCase->execute(input: $baseLayerInput);

    $input = new CreateLayerInput(
        type: LayerType::DISCOUNT->value,
        code: uniqid('LAYER_', true),
        layerId: $baseLayerOutput->layerId,
        discountType: DiscountType::FIXED->value,
        discountValue: 15,
    );
    $useCase = new CreateLayer(
        layerRepository: $layerRepository,
    );
    $output = $useCase->execute($input);
    expect($output)->toBeInstanceOf(CreateLayerOutput::class);
    expect($output->layerId)->toBeString();
});
