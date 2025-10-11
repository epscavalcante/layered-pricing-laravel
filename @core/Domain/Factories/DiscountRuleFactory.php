<?php

namespace Src\Domain\Factories;

use InvalidArgumentException;
use Src\Domain\Enums\DiscountType;
use Src\Domain\ValueObjects\DiscountRule;
use Src\Domain\ValueObjects\FixedDiscountRule;
use Src\Domain\ValueObjects\PercentageDiscountRule;

class DiscountRuleFactory
{
    public static function create(string $type, int $value): DiscountRule
    {
        $discountType = DiscountType::tryFrom($type);

        if (! $discountType) {
            throw new InvalidArgumentException("Tipo de desconto inválido: {$type}");
        }

        return match ($discountType) {
            DiscountType::PERCENTAGE => new PercentageDiscountRule($value),
            DiscountType::FIXED => new FixedDiscountRule($value),
        };
    }
}
