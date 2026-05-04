<?php

namespace Modules\Cart\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Product\App\Models\Addon;
use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\UsedProduct;

class CartRequest extends FormRequest
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
     */
    public function rules(): array
    {
        // Check if this is an update request by checking if cart_item_id exists in route
        $cartItemId = $this->route('cartItem') || $this->route('cart_item_id');
        $isUpdate = !empty($cartItemId);

        $rules = [
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],

            'addons' => ['sometimes', 'array'],
            'addons.*.addon_id' => [
                'required',
                'exists:addons,id',
                'distinct',
            ],
            'addons.*.quantity' => ['required', 'integer', 'min:1', 'max:10'],

            'attributes' => ['sometimes', 'array'],
            'attributes.*' => [
                'required',
                'exists:product_attribute_values,id',
                'distinct',
            ],
        ];

        if (!$isUpdate) {
            $rules['item_type'] = ['required', 'in:product,used_product'];

            if ($this->item_type === 'product') {
                $rules['quantity'] = ['required', 'integer', 'min:1', 'max:100'];
            }

            $rules['product_id'] = ['required_if:item_type,product', 'exists:products,id'];
            $rules['used_product_id'] = [
                'required_if:item_type,used_product',
                'exists:used_products,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $usedProduct = UsedProduct::find($value);
                        if ($usedProduct && $usedProduct->is_sold) {
                            $fail('This used product has already been sold.');
                        }
                        if ($usedProduct && !$usedProduct->is_active) {
                            $fail('This used product is not available.');
                        }
                        $existsInCart = \Modules\Cart\App\Models\CartItem::where('used_product_id', $value)
                            ->where('item_type', 'used_product')
                            ->exists();
                        if ($existsInCart) {
                            $fail('This used product is already in a cart.');
                        }
                    }
                },
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Product selection is required',
            'product_id.exists' => 'Selected product does not exist',
            'quantity.required' => 'Quantity is required',
            'quantity.min' => 'Quantity must be at least 1',
            'quantity.max' => 'Maximum quantity allowed is 100',
            'addons.*.addon_id.required' => 'Addon selection is required',
            'addons.*.addon_id.exists' => 'Selected addon does not exist',
            'addons.*.addon_id.distinct' => 'Duplicate addons are not allowed',
            'addons.*.quantity.required' => 'Addon quantity is required',
            'addons.*.quantity.min' => 'Addon quantity must be at least 1',
            'addons.*.quantity.max' => 'Maximum addon quantity allowed is 10',
            'attributes.*.required' => 'Attribute selection is required',
            'attributes.*.exists' => 'Selected attribute value does not exist',
            'attributes.*.distinct' => 'Duplicate attribute values are not allowed',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Only validate product relationships for CREATE requests
            $cartItemId = $this->route('cartItem') || $this->route('cart_item_id');
            $isUpdate = !empty($cartItemId);

            if (!$isUpdate) {
                $this->validateProductRelationships($validator);
            }
        });
    }

    /**
     * Validate that addons and attributes belong to the selected product
     */
    private function validateProductRelationships($validator)
    {
        $productId = $this->input('product_id');

        if (!$productId) {
            return;
        }

        $product = Product::with(['addons', 'attributes.values'])->find($productId);

        if (!$product) {
            return;
        }

        // Validate addons
        if ($this->has('addons')) {
            $validAddonIds = $product->addons->pluck('id')->toArray();

            foreach ($this->input('addons', []) as $index => $addonData) {
                if (isset($addonData['addon_id']) && !in_array($addonData['addon_id'], $validAddonIds)) {
                    $validator->errors()->add("addons.{$index}.addon_id", 'The selected addon does not belong to this product.');
                }

                // Check if addon is active
                if (isset($addonData['addon_id'])) {
                    $addon = \Modules\Product\App\Models\Addon::find($addonData['addon_id']);
                    if ($addon && !$addon->is_active) {
                        $validator->errors()->add("addons.{$index}.addon_id", 'The selected addon is not available.');
                    }
                }
            }
        }

        // Validate attributes
        if ($this->has('attributes')) {
            $validAttributeValueIds = $product->attributes
                ->flatMap->values
                ->pluck('id')
                ->toArray();

            foreach ($this->input('attributes', []) as $index => $attributeValueId) {
                if (!in_array($attributeValueId, $validAttributeValueIds)) {
                    $validator->errors()->add("attributes.{$index}", 'The selected attribute value does not belong to this product.');
                }
            }
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            returnMessage(false, 'Validation failed', $validator->errors(), 'validation_error')
        );
    }
}
