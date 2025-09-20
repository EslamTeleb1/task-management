<?php

namespace Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
      public function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'due_date'       => 'nullable|date|after_or_equal:today',
            'assignee_id'    => 'nullable|integer|exists:users,id',
            'dependency_ids' => 'nullable|array',
            'dependency_ids.*' => 'integer|exists:tasks,id|different:id', // prevent self-dependency
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
