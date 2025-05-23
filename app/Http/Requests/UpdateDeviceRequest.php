<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:devices,name,' . $this->id,
            'device_type' => 'required',
            'owner' => 'required',
            'health' => 'required',
            'status' => 'required',
            'description' => 'required|max:200',
            'mac_address' => [
                'required',
                'max:17',
                'regex:/^(?:[0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}$/'
            ],
            'ip_address' => 'required',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Device Name is Required',
            'name.unique' => 'Device Name Already Taken',
            'device_type.required' => 'Select Device Type',
            'owner.required' => 'Select Device Owner',
            'health.required' =>  'Select Device Health',
            'status.required' =>  'Select Device Status',
            'description.required' => 'Description Required',
            'description.max' => 'Description can be only 200 characters long',
            'mac_address.required' =>  'Mac Address is Required',
            'ip_address.required' => 'IP Address is Required',
        ];
    }
}
