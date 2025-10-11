<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateLayer;

use Exception;
use Src\Application\Repositories\LayerRepository;
use Src\Application\UseCases\CreateLayer\CreateDiscountLayer\CreateDiscountLayer;
use Src\Application\UseCases\CreateLayer\CreateDiscountLayer\CreateDiscountLayerInput;
use Src\Application\UseCases\CreateLayer\CreateSimpleLayer\CreateSimpleLayer;
use Src\Application\UseCases\CreateLayer\CreateSimpleLayer\CreateSimpleLayerInput;
use Src\Domain\Enums\LayerType;

class CreateLayer
{
    public function __construct(
        private readonly LayerRepository $layerRepository,
    ) {}

    public function execute(CreateLayerInput $input): CreateLayerOutput
    {
        $targetInput = match ($input->type) {
            LayerType::NORMAL->value => new CreateSimpleLayerInput(
                code: $input->code,
            ),
            LayerType::DISCOUNT->value => new CreateDiscountLayerInput(
                layerId: $input->layerId,
                code: $input->code,
                type: $input->discountType,
                value: $input->discountValue,
            ),
            default => throw new Exception('Invalid type')
        };

        $targetUseCase = match ($input->type) {
            LayerType::NORMAL->value => new CreateSimpleLayer(
                layerRepository: $this->layerRepository
            ),
            LayerType::DISCOUNT->value => new CreateDiscountLayer(
                layerRepository: $this->layerRepository,
            ),
            default => throw new Exception('Invalid type')
        };

        $output = $targetUseCase->execute($targetInput);

        return new CreateLayerOutput(
            layerId: $output->layerId
        );
    }
}
