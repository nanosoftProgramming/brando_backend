<?php

namespace Modules\Driver\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DriverUpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $driverId = auth('driver')->id();

        return [
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15', 'unique:drivers,phone,'.$driverId],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            'email' => ['nullable', 'email', 'unique:drivers,email,'.$driverId],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'phone' => 'Phone',
            'image' => 'Image',
            'email' => 'Email',
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
