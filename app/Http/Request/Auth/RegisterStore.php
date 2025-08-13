<?php

namespace App\Http\Request\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponse;

class RegisterStore extends FormRequest
{
    use ApiResponse;
    
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('validations.name'),
            'email' => __('validations.email'),
            'password' => __('validations.password'),
            'password_confirmation' => __('validations.password_confirmation'),
        ];
    }

    public function messages(): array|object
    {
        return [
            'required' => __('validations.required'),
            'same' => __('validations.same'),
            'unique' => __('validations.unique'),
        ];
    }
}