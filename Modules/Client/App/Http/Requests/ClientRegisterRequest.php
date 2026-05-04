<?php

namespace Modules\Client\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:clients,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'phone' => ['required', 'unique:clients,phone'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:1024'],
            'date_of_birth' => ['nullable', 'date'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email Address',
            'password' => 'Password',
            'phone' => 'Phone',
            'image' => 'Image',
            'date_of_birth' => 'Date of Birth',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
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
