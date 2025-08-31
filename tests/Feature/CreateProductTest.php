<?php

use Illuminate\Testing\Fluent\AssertableJson;

it('returns a unprocessable response', function () {
    $response = $this->postJson(
        route('products.store_simple'),
        []
    );

    $response->assertUnprocessable();
    $response->assertInvalid([
        'name' => ['The name field is required.'],
    ]);
});


it('returns a successful response', function () {
    $response = $this->postJson(
        route('products.store_simple'),
        [
            'name' => fake()->sentence(),
        ]
    );

    $response->assertCreated();
    $response->assertJson(
        fn(AssertableJson $json) =>
        $json->whereType('product_id', 'string')
    );
});
