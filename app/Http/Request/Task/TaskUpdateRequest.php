<?php

namespace App\Http\Request\Task;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponse;

class TaskUpdateRequest extends FormRequest
{
    use ApiResponse;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'assigned_user_id' => 'nullable|integer',
            'due_date' => 'nullable|date',
            'team_id' => 'required|integer',
            'created_by' => 'required|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => __('validations.task_title'),
            'description' => __('validations.description'),
            'status' => __('validations.status'),
            'assigned_user_id' => __('validations.assigned_user'),
            'due_date' => __('validations.due_date'),
            'team_id' => __('validations.team'),
            'created_by' => __('validations.created_by'),
        ];
    }

    public function messages(): array
    {
        return [
            'required' => __('validations.required'),
            'string' => __('validations.string'),
        ];
    }
}
