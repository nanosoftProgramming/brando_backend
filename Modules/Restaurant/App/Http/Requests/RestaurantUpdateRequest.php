<?php

namespace Modules\Restaurant\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Restaurant\App\Models\WorkingTime;

class RestaurantUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'manager_name' => ['required', 'string', 'max:255'],
            'manager_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            'email' => ['required', 'email', 'unique:admins,email,'.$this->restaurant->manager->id],
            'phone' => ['required', 'string', 'unique:admins,phone,'.$this->restaurant->manager->id],
            'password' => ['nullable', 'string', 'min:6'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            'min_time' => ['nullable', 'integer', 'min:0', 'max:255'],
            'max_time' => ['nullable', 'integer', 'min:0', 'max:255', 'gt:min_time'],
            'working_times' => 'required|array',
            'working_times.*.day' => 'required|in:'.implode(',', WorkingTime::DAYS),
            'working_times.*.is_closed' => 'required|boolean',
            'working_times.*.opening_time' => 'required_if:working_times.*.is_closed,0|date_format:H:i',
            'working_times.*.closing_time' => 'required_if:working_times.*.is_closed,0|date_format:H:i|after:working_times.*.opening_time',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'manager_name' => 'Manager Name',
            'email' => 'Email Address',
            'image' => 'Image',
            'password' => 'Password',
            'phone' => 'Phone Number',
            'manager_image' => 'Manager Image',
            'min_time' => 'Min Time',
            'max_time' => 'Max Time',
            'working_times' => 'Working Times',
            'working_times.*.day' => 'Day',
            'working_times.*.opening_time' => 'Opening Time',
            'working_times.*.closing_time' => 'Closing Time',
            'working_times.*.is_closed' => 'Is Closed',
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
