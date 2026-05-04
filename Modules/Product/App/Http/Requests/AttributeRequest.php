<?php

namespace Modules\Product\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('delete') || $this->is('*/toggle-activate')) {
            return [];
        }
        $rules = [
            'name' => 'required|string|max:255',
            'required' => 'boolean',
            'multi_select' => 'boolean',
            'override_price' => 'boolean',
            'values' => 'required|array',
            'values.*.attribute_value' => 'required|string|max:255',
        ];

        return $rules;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            returnValidationMessage(
                false,
                trans('validation.rules_failed'),
                $validator->errors()->messages(),
                'unprocessable_entity'
            )
        );
    }
}
