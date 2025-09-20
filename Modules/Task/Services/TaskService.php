<?php 

namespace Modules\Task\Services;
use App\Models\User;
use Modules\Task\Models\Task;
use Modules\Task\Repositories\TaskRepository;
use Modules\Task\Repositories\TaskRepositoryInterface;
class TaskService {
    public function __construct(private TaskRepositoryInterface $repo) {}

    public function createTask(array $data, User $currentUser) {
        $data['created_by'] = $currentUser->id;
        $task = $this->repo->create($data);
        if(!empty($data['dependency_ids'])) {
            $task->dependencies()->sync($data['dependency_ids']);
        }
        return $task;
    }

    public function updateTaskStatus(int $id, string $status, User $actor) {
        $task = $this->repo->findById($id);
        // If trying to complete, ensure dependencies completed
        if($status === 'completed') {
            $incompleteDeps = $task->dependencies()->where('status','!=','completed')->count();
            if($incompleteDeps > 0) {
                throw new \Exception('Dependencies not completed');
            }
        }
        return $this->repo->update($id, ['status' => $status]);
    }

    public function updateTask(int $id, array $data, User $actor) {
        // enforce role-based changes (users can only change status)
        if($actor->hasRole('User')) {
            return $this->updateTaskStatus($id, $data['status'] ?? null, $actor);
        }
        return $this->repo->update($id,$data);
    }
}

