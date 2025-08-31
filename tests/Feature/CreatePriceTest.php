<?php

use App\Models\Layer;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Testing\Fluent\AssertableJson;
use Src\Domain\Enums\DiscountType;

it('returns a successful response when create simple price', function () {
    $baseLayer = Layer::factory()->normal()->create();
    $product = Product::factory()->create();

    $response = $this->postJson(
        route('prices.store_simple'),
        [
            'layer_id' => $baseLayer->id,
            'product_id' => $product->id,
            'value' => 34560,
        ]
    );

    $response->assertCreated();
    $response->assertJson(
        fn(AssertableJson $json) =>
        $json->whereType('price_id', 'string')
    );
});

it('returns a successful response when create percentage discount price', function () {
    $baseLayer = Layer::factory()->normal()->create();
    $discountLayer = Layer::factory()
        ->discountable(DiscountType::PERCENTAGE, 10)
        ->for($baseLayer, 'parent')
        ->create();

    $product = Product::factory()->create();
    Price::factory()
        ->for($baseLayer, 'layer')
        ->for($product, 'product')
        ->create([
            'value' => 40000,
        ]);

    $response = $this->postJson(
        route('prices.store_discount'),
        [
            'layer_id' => $discountLayer->id,
            'product_id' => $product->id,
        ]
    );

    $response->assertCreated();
    $response->assertJson(
        fn(AssertableJson $json) =>
        $json->whereType('price_id', 'string')
    );
});

it('returns a unprocessable response', function () {
    $response = $this->postJson(
        route('prices.store_simple'),
        []
    );

    $response->assertUnprocessable();
    $response->assertInvalid([
        'product_id' => ['The product id field is required.'],
        'layer_id' => ['The layer id field is required.'],
        'value' => ['The value field is required.'],
    ]);
});
