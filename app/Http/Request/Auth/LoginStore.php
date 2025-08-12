<?php

namespace App\Http\Request\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponse;

class LoginStore extends FormRequest
{
    use ApiResponse;
    
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => __('validations.email'),
            'password' => __('validations.password'),
        ];
    }

    public function messages(): array|object
    {
        return [
            'required' => __('validations.required'),
        ];
    }
}