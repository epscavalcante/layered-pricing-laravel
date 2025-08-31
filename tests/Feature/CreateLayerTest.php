<?php

use Illuminate\Testing\Fluent\AssertableJson;

it('returns a unprocessable response - normal type', function () {
    $response = $this->postJson(
        route('layers.store_simple'),
        []
    );

    $response->assertUnprocessable();
    $response->assertInvalid([
        'code' => ['The code field is required.'],
    ]);
});

it('returns a unprocessable response - discount type', function () {
    $response = $this->postJson(
        route('layers.store_discount'),
        []
    );

    $response->assertUnprocessable();
    $response->assertInvalid([
        'code' => ['The code field is required.'],
        'parent_id' => ['The parent id field is required.'],
        'type' => ['The type field is required.'],
        'value' => ['The value field is required'],
    ]);
});

it('returns a successful response - normal type', function () {
    $response = $this->postJson(
        route('layers.store_simple'),
        ['code' => fake()->uuid(),]
    );

    $response->assertCreated();
    $response->assertJson(
        fn(AssertableJson $json) =>
        $json->whereType('layer_id', 'string')
    );
});
