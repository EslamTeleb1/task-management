<?php
namespace Modules\Task\Repositories;
use Modules\Task\Models\Task;
use Modules\Task\Repositories\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface {
    public function create(array $data) {
        return Task::create($data);
    }

    public function findById(int $id) {
        return Task::with('dependencies')->findOrFail($id);
    }

    public function filter(array $filters, int $perPage = 10) {
        return Task::query()
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['due_date_from'] ?? null, fn($q, $from) => $q->whereDate('due_date', '>=', $from))
            ->when($filters['due_date_to'] ?? null, fn($q, $to) => $q->whereDate('due_date', '<=', $to))
            ->when($filters['user_id'] ?? null, fn($q, $userId) => $q->where('user_id', $userId))
            ->paginate($perPage);
    }

    public function update(int $id, array $data) {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }
}
