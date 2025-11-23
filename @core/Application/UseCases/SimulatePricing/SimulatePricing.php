<?php

declare(strict_types=1);

namespace Src\Application\UseCases\SimulatePricing;

use Src\Application\Queries\ListPriceQuery\ListPriceQuery;
use Src\Application\Queries\ListPriceQuery\ListPriceQueryInput;
use Src\Application\Repositories\LayerRepository;
use Src\Domain\Entities\Layer;
use Src\Domain\Exceptions\LayerNotFoundException;
use Src\Domain\Services\PriceCalculator;
use Src\Domain\ValueObjects\LayerId;

final class SimulatePricing
{
    public function __construct(
        private readonly LayerRepository $layerRepository,
        private readonly ListPriceQuery $listPriceQuery,
    ) {}

    public function execute(SimulatePricingInput $input): SimulatePricingOutput
    {
        $baseLayerId = $this->layerRepository->findById(LayerId::restore($input->baseLayerId));
        if (is_null($baseLayerId)) {
            throw new LayerNotFoundException;
        }

        $listPriceQueryInput = new ListPriceQueryInput(
            layerIds: [$baseLayerId->getId()],
        );
        $listPriceQueryOutput = $this->listPriceQuery->query($listPriceQueryInput);

        // public string $priceId,
        // public string $layerId,
        // public string $productId,
        // public string $productName,
        // public string $layerCode,
        // public string $layerType,
        // public int $value,

        $prices = array_map(
            callback: function ($price) use ($input) {
                $layer = Layer::create(
                    code: 'SIMULATION',
                    type: $input->operation,
                    discountType: $input->operationType,
                    discountValue: $input->operationValue,
                );

                return [
                    'product_name' => $price->productName,
                    'original_value' => $price->value,
                    'operation' => $input->operation,
                    'operation_type' => $input->operationType,
                    'operation_value' => $input->operationValue,
                    'final_value' => PriceCalculator::calculate(
                        baseValue: $price->value,
                        layer: $layer
                    ),
                ];
            },
            array: $listPriceQueryOutput->items
        );

        return new SimulatePricingOutput($prices);
    }
}
