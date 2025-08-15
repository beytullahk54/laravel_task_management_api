<?php

namespace App\Http\Request\Task;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponse;

class TaskFileRequest extends FormRequest
{
    use ApiResponse;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => __('validations.file'),
        ];
    }

    public function messages(): array
    {
        return [
            'required' => __('validations.required'),
            'file' => __('validations.file'),
            'mimes' => __('validations.mimes'),
            'max' => __('validations.max'),
        ];
    }
}
