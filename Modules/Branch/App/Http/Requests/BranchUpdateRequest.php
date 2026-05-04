<?php

namespace Modules\Branch\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BranchUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $branch = $this->route('branch');
        $admin = auth('admin')->user();
        if ($admin && ! $admin->hasRole('Super Admin')) {
            if ($admin->restaurant_id !== $branch->restaurant_id) {
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
        $branch = $this->route('branch');
        $branchId = $branch->id;

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
            'phone' => ['required', 'string', 'max:255', 'unique:branches,phone,'.$branchId],

            'manager_name' => ['required', 'string', 'max:255'],
            'manager_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            'email' => ['required', 'email', 'unique:admins,email,'.$branch->manager->id],
            'manager_phone' => ['required', 'string', 'unique:admins,phone,'.$branch->manager->id],
            'password' => ['sometimes', 'string', 'min:6'],
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
