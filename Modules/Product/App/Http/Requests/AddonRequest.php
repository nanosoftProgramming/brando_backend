<?php

namespace Modules\Product\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddonRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if (! $this->isMethod('delete') || ! $this->is('*/toggle-activate')) {
            $admin = auth('admin')->user();
            if ($admin && ! $admin->hasRole('Super Admin')) {
                $this->merge([
                    'restaurant_id' => $admin->restaurant_id,
                ]);
            }
        }
    }

    public function authorize(): bool
    {
        $addon = $this->route('addon');
        if (! $addon) {
            return true;
        }
        $admin = auth('admin')->user();
        if ($admin && ! $admin->hasRole('Super Admin')) {
            if ($admin->restaurant_id !== $addon->restaurant_id) {
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
        if ($this->isMethod('delete') || $this->is('*/toggle-activate')) {
            return [];
        }
        $rules = [
            'name' => 'required|string|max:255',
            'price_egp' => 'required|numeric|min:0',
            'price_sar' => 'required|numeric|min:0',
            'image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            'is_active' => 'boolean',
        ];
        if (auth('admin')->check() && auth('admin')->user()->hasRole('Super Admin')) {
            array_merge($rules, [
                'restaurant_id' => 'required|exists:restaurants,id',
            ]);
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
