<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;

class CreateLayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required',
            'type' => [
                'required',
                'string',
                Rule::in(LayerType::cases()),
            ],
            'layer_id' => [
                Rule::when(
                    condition: $this->type === LayerType::DISCOUNT->value,
                    rules: [
                        'required',
                        'string',
                    ],
                    defaultRules: ['nullable']
                ),
            ],
            'discount_type' => [
                Rule::when(
                    condition: $this->type === LayerType::DISCOUNT->value,
                    rules: [
                        'required',
                        'string',
                        Rule::in(DiscountType::cases()),
                    ],
                    defaultRules: ['nullable']
                ),
            ],
            'discount_value' => [
                Rule::when(
                    condition: $this->type === LayerType::DISCOUNT->value,
                    rules: function () {
                        return array_merge(
                            ['required', 'integer'],
                            $this->discount_type === DiscountType::PERCENTAGE->value
                                ? ['between:1,100']
                                : ['gte:1']
                        );
                    },
                    defaultRules: ['nullable']
                ),
            ],
        ];
    }
}
