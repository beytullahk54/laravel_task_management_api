<?php

namespace App\Http\Request\Team;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponse;

class TeamUpdateRequest extends FormRequest
{
    use ApiResponse;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'owner_id' => 'required|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('validations.team_name'),
            'description' => __('validations.description'),
            'owner_id' => __('validations.owner'),
        ];
    }

    public function messages(): array
    {
        return [
            'required' => __('validations.required'),
            'string' => __('validations.string')
        ];
    }
}
