<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsersRequest extends FormRequest
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
            'fname' => 'required',
            'lname' => 'required',
            'title' => 'required',
            'role' => 'required',
            'password' => 'same:confirm-password',
            'email' => 'required|email|unique:users,email'.$this->id,
            'phonenumber' => 'required|numeric|digits:10|unique:users,phonenumber' . $this->id,
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
            'fname.required' => 'First Name is required',
            'lname.required' => 'Last Name is required',
            'title.required' => 'Title is required',
            'role.required' =>  'Role is required',
            'email.required' => 'Email is required',
            'phonenumber.required' => 'Phone Number is required',
            'phonenumber.numeric' => 'Phone Number should be numeric',
            'phonenumber.digits' => 'Phone Number should be 10 Digit Long',
            'phonenumber.unique' => 'Phone Number is already taken !!',
        ];
    }
}
