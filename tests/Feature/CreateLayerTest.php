<?php

use App\Models\Layer;
use Illuminate\Testing\Fluent\AssertableJson;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;

describe(
    description: 'Unprocessable response',
    tests: function () {
        it('required base fields', function () {
            $response = $this->postJson(route('layers.store'), []);
            $response->assertUnprocessable();
            $response->assertInvalid([
                'code' => ['The code field is required.'],
                'type' => ['The type field is required.'],
            ]);
        });

        it('required fields when type is discount', function () {
            $response = $this->postJson(route('layers.store'), ['code' => 'discount', 'type' => LayerType::DISCOUNT->value]);
            $response->assertUnprocessable();
            $response->assertInvalid([
                'layer_id' => ['The layer id field is required.'],
                'discount_type' => ['The discount type field is required.'],
                'discount_value' => ['The discount value field is required.'],
            ]);
        });

        it('discount type is invalid', function () {
            $response = $this->postJson(
                route('layers.store'),
                [
                    'code' => 'discount',
                    'type' => LayerType::DISCOUNT->value,
                    'layer_id' => 'layer_id',
                    'discount_type' => 'invalid',
                ]
            );
            $response->assertUnprocessable();
            $response->assertInvalid([
                'discount_type' => ['The selected discount type is invalid.'],
                'discount_value' => ['The discount value field is required.'],
            ]);
        });

        it('discount value invalid for fixed discount', function ($discountValue) {
            $response = $this->postJson(
                route('layers.store'),
                [
                    'code' => 'discount',
                    'type' => LayerType::DISCOUNT->value,
                    'layer_id' => 'layer_id',
                    'discount_type' => DiscountType::FIXED->value,
                    'discount_value' => $discountValue,
                ]
            );
            $response->assertUnprocessable();
            $response->assertInvalid([
                'discount_value' => ['The discount value field must be greater than or equal to 1.'],
            ]);
        })->with([[0], [-10], [-67]]);

        it('discount value invalid for percentage type', function (int $discountValue) {
            $response = $this->postJson(
                route('layers.store'),
                [
                    'code' => 'discount',
                    'layer_id' => 'layer_id',
                    'type' => LayerType::DISCOUNT->value,
                    'discount_type' => DiscountType::PERCENTAGE->value,
                    'discount_value' => $discountValue,
                ]
            );
            $response->assertUnprocessable();
            $response->assertInvalid([
                'discount_value' => ['The discount value field must be between 1 and 100.'],
            ]);
        })->with([[0], [-10], [-67], [101], [135], [200]]);
    }
);

describe(
    description: 'Created response',
    tests: function () {
        it('creates a normal layer', function () {
            $response = $this->postJson(
                route('layers.store'),
                [
                    'type' => LayerType::NORMAL->value,
                    'code' => 'layer',
                ]
            );
            $response->assertCreated();
            $response->assertJson(fn (AssertableJson $json) => $json->whereType('layer_id', 'string'));
        });

        it('creates a fixed discount layer', function () {
            $layer = Layer::factory()->normal()->create();
            $response = $this->postJson(
                route('layers.store'),
                [
                    'code' => 'layer',
                    'type' => LayerType::DISCOUNT->value,
                    'layer_id' => $layer->id,
                    'discount_type' => DiscountType::FIXED->value,
                    'discount_value' => 15,
                ]
            );
            $response->assertCreated();
            $response->assertJson(fn (AssertableJson $json) => $json->whereType('layer_id', 'string'));
        });

        it('creates a percentage discount layer', function () {
            $layer = Layer::factory()->normal()->create();
            $response = $this->postJson(
                route('layers.store'),
                [
                    'code' => 'layer',
                    'type' => LayerType::DISCOUNT->value,
                    'layer_id' => $layer->id,
                    'discount_type' => DiscountType::PERCENTAGE->value,
                    'discount_value' => 15,
                ]
            );
            $response->assertCreated();
            $response->assertJson(fn (AssertableJson $json) => $json->whereType('layer_id', 'string'));
        });
    }
);
