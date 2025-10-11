<?php

namespace Database\Seeders;

use App\Models\Layer;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CheckoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $originalSealLayer = Layer::factory()->normal()->create(['code' => 'SELO_ORIGINAL']);
        $oneHundredSealLayer = Layer::factory()->normal()->create(['code' => 'SELO_100']);
        $twoHundredSealLayer = Layer::factory()->normal()->create(['code' => 'SELO_200']);

        $basicPlanProduct = Product::factory()->create(['name' => 'Plano básico']);
        $microPlan = Product::factory()->create(['name' => 'Plano micro']);
        $smalPlanProduct = Product::factory()->create(['name' => 'Plano small']);

        Price::factory()
            ->for($originalSealLayer, 'layer')
            ->for($basicPlanProduct, 'product')
            ->create(['value' => 8990]);

        Price::factory()
            ->for($oneHundredSealLayer, 'layer')
            ->for($basicPlanProduct, 'product')
            ->create(['value' => 11990]);

        Price::factory()
            ->for($twoHundredSealLayer, 'layer')
            ->for($basicPlanProduct, 'product')
            ->create(['value' => 13990]);

        Price::factory()
            ->for($originalSealLayer, 'layer')
            ->for($microPlan, 'product')
            ->create(['value' => 15990]);

        Price::factory()
            ->for($oneHundredSealLayer, 'layer')
            ->for($basicPlanProduct, 'product')
            ->create(['value' => 19990]);

        Price::factory()
            ->for($twoHundredSealLayer, 'layer')
            ->for($basicPlanProduct, 'product')
            ->create(['value' => 26990]);

        Price::factory()
            ->for($originalSealLayer, 'layer')
            ->for($smalPlanProduct, 'product')
            ->create(['value' => 24990]);

        Price::factory()
            ->for($oneHundredSealLayer, 'layer')
            ->for($basicPlanProduct, 'product')
            ->create(['value' => 28990]);

        Price::factory()
            ->for($twoHundredSealLayer, 'layer')
            ->for($basicPlanProduct, 'product')
            ->create(['value' => 36990]);
    }
}
