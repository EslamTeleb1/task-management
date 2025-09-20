<?php

namespace Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
      public function rules(): array
    {
        return [
            'title'          => 'sometimes|required|string|max:255',
            'description'    => 'sometimes|nullable|string',
            'due_date'       => 'sometimes|nullable|date|after_or_equal:today',
            'assignee_id'    => 'sometimes|nullable|integer|exists:users,id',
            'status'         => 'sometimes|required|in:pending,in_progress,completed,canceled',
            'dependency_ids' => 'sometimes|array',
            'dependency_ids.*' => 'integer|exists:tasks,id|different:id',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
