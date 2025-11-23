<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimulatePricingRequest extends FormRequest
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
            'base_layer_id' => ['required', 'string'],
            'operation' => ['required', 'string',],
            'operation_type' => ['required', 'string',],
            'operation_value' => ['required', 'numeric', 'min:0'],
        ];
    }
}
