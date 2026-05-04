<?php

namespace Modules\Order\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'rate' => 'required|integer|min:1|max:5',
            'restaurant_rate' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
            'restaurant_comment' => 'nullable|string|max:255',
            'products' => 'nullable|array',
            'products.*.product_id' => 'required_if:products,!=,null|exists:products,id',
            'products.*.rate' => 'required_if:products.*.product_id,!=,null|integer|min:1|max:5',
            'products.*.comment' => 'nullable|string|max:255',
        ];
    }

    public function authorize()
    {
        $order = $this->route('order');
        if ($order->client_id !== auth('client')->user()->id) {
            throw new HttpResponseException(
                returnUnauthorizedMessage(
                    false,
                    trans('validation.unauthorized'),
                    null
                )
            );
        }

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
