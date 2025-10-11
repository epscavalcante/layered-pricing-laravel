<?php

namespace App\Repositories;

use App\Models\Product as ProductModel;
use Src\Application\Repositories\ProductRepository;
use Src\Domain\Entities\Product;
use Src\Domain\ValueObjects\ProductId;

class ProductModelRepository implements ProductRepository
{
    public function findById(ProductId $productId): ?Product
    {
        $productModel = ProductModel::query()->find($productId->getValue());

        if (is_null($productModel)) {
            return null;
        }

        return $this->toEntity($productModel);
    }

    public function save(Product $product): void
    {
        $productModel = $this->toModel($product);

        $productModel->save();
    }

    private function toModel(Product $product): ProductModel
    {
        $productModel = new ProductModel([
            'id' => $product->getId(),
            'name' => $product->getName(),
        ]);

        return $productModel;
    }

    private function toEntity(ProductModel $productModel): Product
    {
        return Product::restore(
            id: $productModel->id,
            name: $productModel->name,
        );
    }
}
