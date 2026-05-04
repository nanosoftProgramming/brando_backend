<?php

namespace Modules\Client\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // لو في صلاحيات معينة، تقدر تضيفها هنا لاحقاً
    }

    public function rules(): array
    {
        // In this project, updates may be sent as POST (not PUT/PATCH),
        // so we detect update requests by the presence of the {address} route param.
        $isUpdate = $this->route('address') !== null;

        return [
            'title' => ($isUpdate ? 'sometimes' : 'required') . '|string|max:255',
            'city_id' => ($isUpdate ? 'sometimes' : 'required') . '|exists:cities,id',
            'latitude' => ($isUpdate ? 'sometimes' : 'required') . '|numeric|between:-90,90',
            'longitude' => ($isUpdate ? 'sometimes' : 'required') . '|numeric|between:-180,180',
            'block' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'default' => 'nullable|boolean',
            'phone' => $isUpdate ? 'sometimes|required' : 'required',
        ];
    }
}
