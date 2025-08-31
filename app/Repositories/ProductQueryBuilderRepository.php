<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Src\Domain\Entities\Product;
use Src\Application\Repositories\ProductRepository;
use Src\Domain\ValueObjects\ProductId;

class ProductQueryBuilderRepository implements ProductRepository
{
    public function findById(ProductId $productId): ?Product
    {
        $productModel = DB::table('products')->find($productId->getValue());

        if (is_null($productModel)) {
            return null;
        }

        return $this->toEntity($productModel);
    }

    public function save(Product $product): void
    {
        DB::table('products')
            ->upsert(
                values: $this->toArray($product),
                uniqueBy: ['id'],
                update: ['name']
            );
    }

    private function toArray(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
        ];
    }

    private function toEntity(object $product): Product
    {
        return Product::restore(
            id: $product->id,
            name: $product->name,
        );
    }
}
