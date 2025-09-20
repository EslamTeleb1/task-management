<?php

namespace Modules\Task\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Task\Transformers\TaskResource;
use Symfony\Component\HttpFoundation\Response;
use Modules\Task\Http\Requests\StoreTaskRequest;
use Modules\Task\Http\Requests\UpdateTaskRequest;
use Modules\Task\Repositories\TaskRepositoryInterface;
use Modules\Task\Services\TaskService;
 
class TaskController extends Controller
{
    public function __construct(
        private TaskService $service,
        private TaskRepositoryInterface $repo
    ) {}

    /**
     * List tasks with optional filters (status, due date, assignee).
     */
    public function index()
    {
        try {
               $filters = request()->only(['status','due_from','due_to','assignee_id']);
                $perPage = request()->get('per_page', 15);

                $tasks = $this->repo->filter($filters, $perPage);

                return response()->json(["message"=>"tasks retrived successfully ","data"=> TaskResource::collection($tasks)],Response::HTTP_OK );
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch tasks', 'message' => $e->getMessage()], 500);
        }
       
    }

    /**
     * Show a single task with dependencies.
     */
    public function show(int $id)
    {
        try {
                $task = $this->repo->findById($id);
                return response()->json(["message"=>"task retrived successfully ","data"=> new TaskResource($task)],Response::HTTP_OK );
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch task', 'message' => $e->getMessage()], 500);
        }   
       
    }

    /**
     * Create a new task (Manager only).
     */
    public function store(StoreTaskRequest $request)
    {
        try {
           $task =  $this->service->createTask(
                $request->validated(),
                auth()->user()
            );
            return response()->json(['message' => 'Task created successfully',"data"=>$task], Reasponse::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create task', 'message' => $e->getMessage()], 500);
        }
        
    }

    /**
     * Update a task (Manager: all fields, User: only status).
     */
    public function update(UpdateTaskRequest $request, int $id)
    {
        try {
            $task = $this->service->updateTask(
                $id,
                $request->validated(),
                auth()->user()
            );

            return response()->json(['message' => 'Task updated successfully', 'data' => $task]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update task', 'message' => $e->getMessage()], Response::HTTP_FAILED_DEPENDENCY);
        }

    }

    /**
     * Add dependencies to an existing task (Manager only).
     */
    public function addDependencies(int $id)
    {
        try{
              request()->validate([
            'dependency_ids' => 'required|array',
            'dependency_ids.*' => 'integer|exists:tasks,id|different:id',
        ]);

        $task = $this->repo->findById($id);
        $task->dependencies()->syncWithoutDetaching(request('dependency_ids'));

        return response()->json(['message' => 'Dependencies added successfully',"data"=>$task], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add dependencies', 'message' => $e->getMessage()], Response::HTTP_FAILED_DEPENDENCY);
        }

      
    }

    function updateTaskStatus(int $id) {
        try {
                request()->validate([
                'status' => 'required|in:pending,in_progress,completed,canceled',
            ]);

            $task = $this->service->updateTaskStatus(
                $id,
                request('status'),
                auth()->user()
            );

            return response()->json(["message"=>"Task successfully updated","data"=>$task]);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update status', 'message' => $e->getMessage()], Response::HTTP_FAILED_DEPENDENCY);

        }
       
    }
}
