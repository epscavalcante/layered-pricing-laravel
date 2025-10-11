<?php

namespace App\Repositories;

use App\Models\Price as PriceModel;
use Src\Application\Repositories\PriceRepository;
use Src\Domain\Entities\Price;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\PriceId;
use Src\Domain\ValueObjects\ProductId;

class PriceModelRepository implements PriceRepository
{
    public function existsByLayerIdAndProductId(LayerId $layerId, ProductId $productId): bool
    {
        return PriceModel::query()
            ->where('layer_id', $layerId->getValue())
            ->where('product_id', $productId->getValue())
            ->exists();
    }

    /**
     * @param  ProductId[]  $productIds
     * @return Price[]
     */
    public function findByLayerIdAndProductIds(LayerId $layerId, array $productIds): array
    {
        $priceModels = PriceModel::query()
            ->where('layer_id', $layerId->getValue())
            ->whereIn(
                'product_id',
                array_map(
                    callback: fn (ProductId $productId) => $productId->getValue(),
                    array: $productIds
                )
            )
            ->get();

        if ($priceModels->count() === 0) {
            return [];
        }

        return array_map(
            callback: fn (PriceModel $priceModel) => $this->toEntity($priceModel),
            array: $priceModels->all()
        );
    }

    public function findByLayerIdAndProductId(LayerId $layerId, ProductId $productId): ?Price
    {
        $priceModel = PriceModel::query()
            ->where('layer_id', $layerId->getValue())
            ->where('product_id', $productId->getValue())
            ->first();

        if (is_null($priceModel)) {
            return null;
        }

        return $this->toEntity($priceModel);
    }

    public function findById(PriceId $priceId): ?Price
    {
        $priceModel = PriceModel::query()->find($priceId->getValue());

        if (is_null($priceModel)) {
            return null;
        }

        return $this->toEntity($priceModel);
    }

    public function save(Price $price): void
    {
        $priceModel = $this->toModel($price);

        $priceModel->save();
    }

    private function toModel(Price $price): PriceModel
    {
        $productModel = new PriceModel([
            'id' => $price->getId(),
            'layer_id' => $price->getLayerId(),
            'product_id' => $price->getProductId(),
            'value' => $price->getValue(),
        ]);

        return $productModel;
    }

    private function toEntity(PriceModel $priceModel): Price
    {
        return Price::restore(
            id: $priceModel->id,
            layerId: $priceModel->layer_id,
            productId: $priceModel->product_id,
            value: $priceModel->value,
        );
    }
}
