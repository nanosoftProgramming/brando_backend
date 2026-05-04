<?php

namespace Modules\Driver\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DriverStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $admin = auth('admin')->user();

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers,email',
            'phone' => 'required|string|unique:drivers,phone',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'license_id' => 'required|string|max:255|unique:drivers,license_id',
            'city_id' => 'required|exists:cities,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_active' => 'nullable|boolean',
        ];

    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
