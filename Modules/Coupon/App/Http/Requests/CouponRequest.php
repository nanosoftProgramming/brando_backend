<?php

namespace Modules\Coupon\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('delete') || $this->is('*/toggle-activate')) {
            return [];
        }
        $this->route('coupon') ? $codeRule = 'required|unique:coupons,code,'.$this->route('coupon')->id : $codeRule = 'required|unique:coupons,code';
        $rules = [
            'code' => $codeRule,
            // 'num_of_uses' => 'required|numeric',
            'type' => 'required|in:1,2',
            'value' => 'required|numeric',
            // 'limit' => 'required|numeric',
            // 'client_uses' => 'required|numeric',
            // 'discount_on' => 'required|in:1,2,3',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'time_from' => 'nullable|date_format:H:i',
            'time_to' => 'nullable|date_format:H:i',
        ];

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
