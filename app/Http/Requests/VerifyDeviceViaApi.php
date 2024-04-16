<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class VerifyDeviceViaApi extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Make sure you actually handle authorization if needed
    }

    public function rules(): array
    {
        return [
            'mac_address' => 'required|string',
            'apikey' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        Log::error('Validation failed:', $validator->errors()->toArray());
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
