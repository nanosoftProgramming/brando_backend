<?php

namespace Modules\Order\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'branch_id'    => 'required|exists:branches,id',
            'address_id' => 'required|exists:addresses,id',
            'payment_type' => 'required|in:cash,visa',

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
