<?php

namespace Modules\Client\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientUpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $clientId = auth('client')->id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15', 'unique:clients,phone,'.$clientId],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            'email' => ['required', 'email', 'unique:clients,email,'.$clientId],
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
            'phone' => 'Phone',
            'image' => 'Image',
            'email' => 'Email',
            'date_of_birth' => 'Date of Birth',
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
