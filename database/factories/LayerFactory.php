<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;
use Src\Domain\Factories\DiscountRuleFactory;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\Ulid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Layer>
 */
class LayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Ulid::create(),
            'code' => fake()->uuid(),
            'type' => array_rand(LayerType::cases()),
            'parent_id' => null,
            'discount_type' => null,
            'discount_value' => null,
        ];
    }

    /**
     * Indicate that the model's type is normal
     */
    public function normal(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => LayerType::NORMAL->value,
        ]);
    }

    /**
     * Indicate that the model's type is discount
     */
    public function discountable(DiscountType $discountType, int $value): static
    {
        $discountRule = DiscountRuleFactory::create(
            type: $discountType->value,
            value: $value
        );

        return $this->state(fn(array $attributes) => [
            'type' => LayerType::DISCOUNT->value,
            'discount_type' => $discountRule->getType()->value,
            'discount_value' => $discountRule->getValue(),
        ]);
    }
}
