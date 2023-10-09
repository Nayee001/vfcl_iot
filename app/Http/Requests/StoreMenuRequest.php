<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'permission_id' => 'required',
            'submenu' => 'required',
            'title' => 'required',
            'link' => 'required',
            'sort' => 'required',
            'target' => 'required',
            'status' => 'required',
            'icon' => 'required',
            'menu_type' => 'required',
        ];
    }
}
