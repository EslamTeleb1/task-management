<?php

namespace Modules\Task\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            'due_date'    => $this->due_date?->format('Y-m-d'),

            'assignee' => $this->assignee ? [
                'id'    => $this->assignee->id,
                'name'  => $this->assignee->name,
                'email' => $this->assignee->email,
            ] : null,

            'creator' => [
                'id'    => $this->creator->id,
                'name'  => $this->creator->name,
                'email' => $this->creator->email,
            ],

            // Dependencies: include id + title
            'dependencies' => $this->dependencies->map(fn ($task) => [
                'id'    => $task->id,
                'title' => $task->title,
            ]),

            'created_at'   => $this->created_at->toDateTimeString(),
            'updated_at'   => $this->updated_at->toDateTimeString(),
        ];
    }
}
