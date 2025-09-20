<?php

namespace Modules\Task\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model {
    protected $fillable = ['title','description','status','due_date','assignee_id','created_by'];

    public function assignee() {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dependencies() {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_task_id');
    }

    public function dependents() {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on_task_id', 'task_id');
    }
}
