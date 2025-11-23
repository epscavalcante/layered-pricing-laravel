<?php

namespace App\Console\Commands;

use App\Models\Layer;
use App\Models\Price;
use Illuminate\Console\Command;
use Src\Application\UseCases\SimulatePricing\SimulatePricing;
use Src\Application\UseCases\SimulatePricing\SimulatePricingInput;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;
use Src\Domain\Services\PriceCalculator;

use function Laravel\Prompts\select;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class SimulatePricingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:simulate-pricing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle(SimulatePricing $simulatePricing)
    {
        // desconto ou aumento de preço
        $operationMethodSelected = select(
            label: 'Operation Type',
            options: [
                LayerType::DISCOUNT->value => 'Desconto',
            ],
            required: true,
        );

        // desconto/aumento percentual ou fixa
        $operationMethodTypeSelected = select(
            label: 'Operation Method',
            options: [
                DiscountType::FIXED->value => 'Fixo',
                DiscountType::PERCENTAGE->value => 'Percentual',
            ],
            required: true,
        );

        $operationValue = (int) text(
            label: 'Operation Value',
            required: true,
            validate: function ($value) use ($operationMethodTypeSelected) {
                if (! is_numeric($value) || (int) $value <= 0) {
                    return 'O valor deve ser um número maior que zero.';
                }
                if ($operationMethodTypeSelected === DiscountType::PERCENTAGE->value && ((int) $value > 100)) {
                    return 'O valor percentual não pode ser maior que 100.';
                }

                return null;
            },
        );

        $baseLayers = Layer::query()->where('type', 'NORMAL')->get();

        $baseLayerSelected = select(
            label: 'Base Layer Code',
            options: $baseLayers->pluck('code', 'id')->toArray(),
            required: true,
        );

        $simulatePricingInput = new SimulatePricingInput(
            baseLayerId: $baseLayerSelected,
            operation: $operationMethodSelected,
            operationType: $operationMethodTypeSelected,
            operationValue: $operationValue
        );

        $simulatePricingOutput = $simulatePricing->execute($simulatePricingInput);

        table(
            headers: ['Product Name', 'Original Value', 'Operation', 'Operation Type', 'Value to Operate', 'Final value'],
            rows: array_map(
                callback: fn ($item) => [
                    $item['product_name'],
                    $item['original_value'],
                    $item['operation'],
                    $item['operation_type'],
                    $item['operation_value'],
                    $item['final_value'],
                ],
                array: $simulatePricingOutput->items
            )
        );
    }

    /**
     * Execute the console command.
     */
    public function handle2()
    {
        // desconto ou aumento de preço
        $operationMethodSelected = select(
            label: 'Operation Type',
            options: [
                LayerType::DISCOUNT->value => 'Desconto',
            ],
            required: true,
        );

        // desconto/aumento percentual ou fixa
        $operationMethodTypeSelected = select(
            label: 'Operation Method',
            options: [
                DiscountType::FIXED->value => 'Fixo',
                DiscountType::PERCENTAGE->value => 'Percentual',
            ],
            required: true,
        );

        $operationValue = (int) text(
            label: 'Operation Value',
            required: true,
            validate: function ($value) use ($operationMethodTypeSelected) {
                if (! is_numeric($value) || (int) $value <= 0) {
                    return 'O valor deve ser um número maior que zero.';
                }
                if ($operationMethodTypeSelected === DiscountType::PERCENTAGE->value && ((int) $value > 100)) {
                    return 'O valor percentual não pode ser maior que 100.';
                }

                return null;
            },
        );

        $baseLayers = Layer::query()->where('type', 'NORMAL')->get();

        $baseLayerSelected = select(
            label: 'Base Layer Code',
            options: $baseLayers->pluck('code', 'id')->toArray(),
            required: true,
        );

        $prices = Price::query()
            ->where('layer_id', $baseLayerSelected)
            ->get(['product_id', 'value']);

        $newPrices = $prices->map(function ($price) use ($operationMethodSelected, $operationMethodTypeSelected, $operationValue) {
            // por enquanto só vai funcionar para desconto
            $price->operation = $operationMethodSelected;
            $price->operation_type = $operationMethodTypeSelected;
            $price->operation_value = $operationValue;
            $price->final_value = PriceCalculator::calculateDiscount(
                baseValue: $price->value,
                discountType: $operationMethodTypeSelected,
                discountValue: $operationValue
            );

            return $price;
        })->toArray();
        table(
            headers: ['ProductId', 'Orignal Value', 'Operation', 'Operation Type', 'Value to Operate', 'Final value'],
            rows: $newPrices
        );
    }
}
