<?php

namespace Database\Seeders;

use App\Models\Layer;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Price::factory()
            ->count(100)
            ->make()
            ->each(function (Price $price) {
                $layer = Layer::inRandomOrder()->first() ?? Layer::factory()->create();
                $price->layer()->associate($layer);
                $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
                $price->product()->associate($product);
                $price->save();
            });
    }
};
