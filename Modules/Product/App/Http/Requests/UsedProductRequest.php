<?php

namespace Modules\Product\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UsedProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $usedProductId = $this->route('id');

        if (!$usedProductId) {
            return true;
        }

        $usedProduct = \Modules\Product\App\Models\UsedProduct::find($usedProductId);

        if (!$usedProduct) {
            throw new HttpResponseException(
                returnMessage(false, 'Used product not found', null, 'not_found')
            );
        }

        if ($usedProduct->client_id !== auth('client')->id()) {
            throw new HttpResponseException(
                returnMessage(false, 'Unauthorized: You can only modify your own products', null, 'forbidden')
            );
        }

        return true;
    }

    public function rules(): array
    {
        $usedProductId = $this->route('id');

        $rules = [
            'code' => 'nullable|string|max:255|unique:used_products,code,' . $usedProductId,
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_egp' => 'nullable|numeric|min:0',
            'price_sar' => 'nullable|numeric|min:0',
            'discounted_price_egp' => 'nullable|numeric|min:0',
            'discounted_price_sar' => 'nullable|numeric|min:0',
            'image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Product code is required',
            'code.unique' => 'This product code already exists',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
            'name.required' => 'Product name is required',
            'price_egp.required' => 'Price in EGP is required',
            'price_egp.numeric' => 'Price in EGP must be a number',
            'price_sar.required' => 'Price in SAR is required',
            'price_sar.numeric' => 'Price in SAR must be a number',
            'image.image' => 'File must be an image',
            'image.mimes' => 'Image must be jpg, jpeg, png, or webp',
            'image.max' => 'Image size must not exceed 1MB',
        ];
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
