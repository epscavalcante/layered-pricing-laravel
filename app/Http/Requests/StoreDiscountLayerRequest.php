<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountLayerRequest extends FormRequest
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
            'parent_id' => 'required|string',
            'type' => 'required|string|in:FIXED,PERCENTAGE',
            'value' => 'required|integer' // se % deve ser entre 1 e 100, se $ deve ser > 1
        ];
    }
}
