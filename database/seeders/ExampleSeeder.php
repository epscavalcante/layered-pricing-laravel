<?php

namespace Database\Seeders;

use App\Models\Layer;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Src\Domain\Enums\DiscountType;

class ExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = ['plasctic', 'metal', 'glass'];
        $locations = ['MT', 'SP'];
        $years = range(2022, 2024);

        $baseLayer = Layer::factory()->normal()->create(['code' => 'BASE']);
        $baseLayerHubspot = Layer::factory()->normal()->create(['code' => 'HUBSPOT']);
        $baseLayerCheckout = Layer::factory()->normal()->create(['code' => 'CHECKOUT']);

        $discountFixedToHubspotLayer = Layer::factory()
            ->for($baseLayerHubspot, 'parent')
            ->discountable(DiscountType::FIXED, 80)
            ->create(['code' => 'DISCOUNT_FIXED_TO_BASE_HUBSPOT']);
        $discountPercentToHubdpotLayer = Layer::factory()
            ->for($baseLayerHubspot, 'parent')
            ->discountable(DiscountType::PERCENTAGE, 5)
            ->create(['code' => 'DISCOUNT_PERCENTAGE_TO_BASE_HUBSPOT']);

        $discountFixedToCheckoutLayer = Layer::factory()
            ->for($baseLayerCheckout, 'parent')
            ->discountable(DiscountType::FIXED, 50)
            ->create(['code' => 'DISCOUNT_FIXED_TO_BASE_CHECKOUT']);
        $discountPercentToCheckoutLayer = Layer::factory()
            ->for($baseLayerCheckout, 'parent')
            ->discountable(DiscountType::PERCENTAGE, 2)
            ->create(['code' => 'DISCOUNT_PERCENTAGE_TO_BASE_CHECKOUT']);

        foreach ($materials as $material) {
            foreach ($locations as $location) {
                foreach ($years as $year) {
                    $code = strtoupper("{$material}{$year}{$location}");
                    $product = Product::factory()->create(['name' => $code]);
                    // base price
                    $basePrice = Price::factory()->create([
                        'layer_id' => $baseLayer->id,
                        'product_id' => $product->id,
                        'value' => rand(1000, 50000),
                    ]);

                    $basePriceHubspot = Price::factory()->create([
                        'layer_id' => $baseLayerHubspot->id,
                        'product_id' => $product->id,
                        'value' => rand(1000, 50000),
                    ]);

                    $basePriceCheckout = Price::factory()->create([
                        'layer_id' => $baseLayerCheckout->id,
                        'product_id' => $product->id,
                        'value' => rand(1000, 50000),
                    ]);

                    if ($location === 'MT') {
                        if ($material === 'metal') {
                            if ($year === 2022) {
                                // hubspot prices - discount fixed price
                                Price::factory()->create([
                                    'layer_id' => $discountFixedToHubspotLayer->id,
                                    'product_id' => $product->id,
                                    'value' => $basePriceHubspot->value - $discountFixedToHubspotLayer->discount_value,
                                ]);

                                // checkout prices - discount percentage price
                                Price::factory()->create([
                                    'layer_id' => $discountPercentToCheckoutLayer->id,
                                    'product_id' => $product->id,
                                    'value' => $basePriceCheckout->value * (1 - $discountPercentToCheckoutLayer->discount_value / 100),
                                ]);
                            }

                            if ($year === 2023) {
                                // hubspot prices - discount fixed price
                                Price::factory()->create([
                                    'layer_id' => $discountPercentToHubdpotLayer->id,
                                    'product_id' => $product->id,
                                    'value' => $basePriceHubspot->value * (1 - $discountPercentToHubdpotLayer->discount_value / 100),
                                ]);

                                // checkout prices - discount percentage price
                                Price::factory()->create([
                                    'layer_id' => $discountFixedToCheckoutLayer->id,
                                    'product_id' => $product->id,
                                    'value' => $basePriceCheckout->value - $discountFixedToCheckoutLayer->discount_value,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
