<?php

namespace Modules\Branch\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BranchRequest extends FormRequest
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
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('delete') || $this->is('*/toggle-activate')) {
            return [];
        }

        return [
            'name' => 'required|string|max:255',
            'restaurant_id' => 'required|exists:restaurants,id',
            'city_id' => 'required|exists:cities,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'block' => 'required|string',
            'street' => 'required|string',
            'building_number' => 'required|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'phone' => ['required', 'string', 'max:255', 'unique:branches,phone,'],
            'manager_name' => ['required', 'string', 'max:255'],
            'manager_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            'email' => ['required', 'email', 'unique:admins,email,'],
            'manager_phone' => ['required', 'string', 'unique:admins,phone,'],
            'password' => ['required', 'string', 'min:6'],
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
