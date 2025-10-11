<?php

namespace Database\Seeders;

use App\Models\Layer;
use Illuminate\Database\Seeder;
use Src\Domain\Enums\DiscountType;

class LayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseLayers = Layer::factory()->normal()->count(rand(5, 10))->create();
        $baseLayers->each(function (Layer $layer) {
            $discountTypes = DiscountType::cases();
            $discountType = $discountTypes[array_rand($discountTypes)];
            Layer::factory()
                ->for($layer, 'parent')
                ->discountable(
                    $discountType,
                    rand(1, 100)
                )->count(rand(1, 3))
                ->create();
        });
    }
}
