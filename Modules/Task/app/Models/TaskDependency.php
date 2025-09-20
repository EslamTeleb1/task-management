<?php

namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDependency extends Model
{
    protected $table = 'task_dependencies';

    protected $fillable = [
        'task_id',
        'depends_on_task_id',
    ];

    /**
     * The main task that has a dependency.
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    /**
     * The task that must be completed before the main task.
     */
    public function dependsOn()
    {
        return $this->belongsTo(Task::class, 'depends_on_task_id');
    }
}
