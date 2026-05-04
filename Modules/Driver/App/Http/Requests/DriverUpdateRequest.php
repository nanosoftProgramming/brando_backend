<?php

namespace Modules\Driver\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DriverUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:drivers,email,'.$this->id,
            'phone' => 'nullable|string|unique:drivers,phone,'.$this->id,
            'password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'is_active' => 'nullable|in:0,1',
            'verify_code' => 'nullable|string|max:10',
            'license_id' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_available' => 'nullable|in:0,1',
            'fcm_token' => 'nullable|string',
            'allow_notification' => 'nullable|in:0,1',
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
