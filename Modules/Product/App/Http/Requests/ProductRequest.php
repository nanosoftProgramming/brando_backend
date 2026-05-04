<?php

namespace Modules\Product\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Product\App\Models\Addon;

class ProductRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if (!$this->isMethod('delete') || !$this->is('*/toggle-activate')) {
            $admin = auth('admin')->user();
            if ($admin && !$admin->hasRole('Super Admin')) {
                $this->merge([
                    'restaurant_id' => $admin->restaurant_id,
                ]);
            }
        }
    }

    public function authorize(): bool
    {
        $product = $this->route('product');
        if (!$product) {
            return true;
        }
        $admin = auth('admin')->user();
        if ($admin && !$admin->hasRole('Super Admin')) {
            if ($admin->restaurant_id !== $product->restaurant_id) {
                throw new HttpResponseException(
                    returnUnauthorizedMessage(
                        false,
                        trans('validation.unauthorized'),
                        null
                    )
                );
            }
        }

        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $productId = $product ? $product->id : null;

        $rules = [
            'code' => 'nullable|string|max:255|unique:products,code,' . $productId,
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_egp' => 'nullable|numeric|min:0',
            'price_sar' => 'nullable|numeric|min:0',
            'discounted_price_egp' => 'nullable|numeric|min:0',
            'discounted_price_sar' => 'nullable|numeric|min:0',
            'image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'images' => ['sometimes', 'array'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],

            // Size images (stored in product_size_images table)
            'size_images' => ['sometimes', 'array'],
            'size_images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],

            'addons' => 'sometimes|array',
            'addons.*.addon_id' => [
                'sometimes',
                'exists:addons,id',
                'distinct',
                function ($attribute, $value, $fail) {
                    $restaurantId = $this->input('restaurant_id');
                    $addon = Addon::find($value);
                    if ($addon && $addon->restaurant_id != $restaurantId) {
                        $fail('The selected addon does not belong to the this restaurant.');
                    }
                },
            ],
            'attributes' => ['sometimes', 'array'],
            'attributes.*.attribute_id' => ['required_with:attributes', 'exists:attributes,id'],
            'attributes.*.attribute_value_id' => ['required_with:attributes', 'exists:attribute_values,id'],
            'attributes.*.price_egp' => ['required_with:attributes', 'numeric', 'min:0'],
            'attributes.*.price_sar' => ['required_with:attributes', 'numeric', 'min:0'],
            // 'attributes' => 'sometimes|array',
            // 'attributes.*.name' => ['required', 'string', 'max:255'],
            // 'attributes.*.required' => ['required', 'boolean'],
            // 'attributes.*.multi_select' => ['required', 'boolean'],
            // 'attributes.*.override_price' => ['required', 'boolean'],
            // 'attributes.*.values' => ['required', 'array'],
            // 'attributes.*.values.*.attribute_value' => ['required', 'string', 'max:255'],
            // 'attributes.*.values.*.price_egp' => ['required', 'numeric', 'min:0'],
            // 'attributes.*.values.*.price_sar' => ['required', 'numeric', 'min:0'],

        ];
        if (auth('admin')->check() && auth('admin')->user()->hasRole('Super Admin')) {
            $rules['restaurant_id'] = 'required|exists:restaurants,id';
        }
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
