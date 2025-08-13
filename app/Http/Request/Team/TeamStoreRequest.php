<?php

namespace App\Http\Request\Team;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponse;

class TeamStoreRequest extends FormRequest
{
    use ApiResponse;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'owner_id' => 'required|integer|exists:users,id',
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
            'string' => __('validations.string'),
            'exists' => __('validations.exists'),
        ];
    }
}
