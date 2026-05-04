<?php

namespace Modules\Common\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SliderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        if ($this->method() === 'DELETE') {
            return [];
        }

        $rules = [
            'restaurant_id' => auth('admin')->user()->hasRole('Super Admin') ? ['sometimes', 'nullable', 'exists:restaurants,id'] : [],
        ];

        if ($this->route('slider')) {
            $rules['image'] = ['sometimes', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'];
        } else {
            $rules['image'] = ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'];
        }

        return $rules;

    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'image' => 'Image',
            'restaurant_id' => 'Restaurant',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->route('slider') || $this->method() == 'DELETE') {
            $admin = auth('admin')->user();
            if (! $admin->hasRole('Super Admin')) {
                if ($admin->id !== $this->route('slider')->admin_id) {
                    throw new HttpResponseException(
                        returnMessage(false, 'You are not authorized to update or delete this slider', null, 'unauthorized')
                    );
                }
            }
        }

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
