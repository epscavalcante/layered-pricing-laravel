<?php

namespace App\Repositories;

use App\Models\Layer as LayerModel;
use Src\Domain\Entities\Layer;
use Src\Domain\Enums\LayerType;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\ValueObjects\LayerId;

class LayerModelRepository implements LayerRepository
{
    public function findById(LayerId $layerId): ?Layer
    {
        $layerModel = LayerModel::query()->find($layerId->getValue());

        if (is_null($layerModel)) {
            return null;
        }

        return $this->toEntity($layerModel);
    }

    public function findByIdAndType(LayerId $layerId, LayerType $type): ?Layer
    {
        $layerModel = LayerModel::query()
            ->where('id', $layerId->getValue())
            ->where('type', $type->value)
            ->first();

        if (is_null($layerModel)) {
            return null;
        }

        return $this->toEntity($layerModel);
    }

    public function findByCode(string $code): ?Layer
    {
        $layerModel = LayerModel::query()->where('code', $code)->first();

        if (is_null($layerModel)) {
            return null;
        }

        return $this->toEntity($layerModel);
    }

    public function save(Layer $layer): void
    {
        $layerModel = $this->toModel($layer);

        $layerModel->save();
    }

    private function toModel(Layer $layer): LayerModel
    {
        $layerModel = new LayerModel([
            'id' => $layer->getId(),
            'parent_id' => $layer->getParentId(),
            'type' => $layer->getType(),
            'code' => $layer->getCode(),
            'discount_type' => $layer->getDiscountType(),
            'discount_value' => $layer->getDiscountValue(),
        ]);

        return $layerModel;
    }

    private function toEntity(LayerModel $layerModel): Layer
    {
        return Layer::restore(
            id: $layerModel->id,
            type: $layerModel->type,
            code: $layerModel->code,
            parentId: $layerModel->parent_id,
            discountType: $layerModel->discount_type,
            discountValue: $layerModel->discount_value,
        );
    }
}
