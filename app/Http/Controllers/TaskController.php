<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Task;
use App\Http\Request\Task\TaskStoreRequest;
use App\Http\Request\Task\TaskUpdateRequest;
use App\Http\Request\Task\TaskFileRequest;
use App\Services\TaskService;

class TaskController extends Controller
{
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        try {
            $cacheKey = sprintf(
                'tasks:%s:%s:%s:%s',
                request()->team_id ?? 'null',
                request()->assigned_user_id ?? 'null', 
                request()->status ?? 'null',
                request()->title ?? 'null'
            );

            $data = Cache::remember($cacheKey, 60, function () {
                $tasks = Task::query();

                if (request()->has('team_id')) {
                    $tasks->where('team_id', request()->team_id);
                }

                if (request()->has('assigned_user_id')) {
                    $tasks->where('assigned_user_id', request()->assigned_user_id);
                }

                if (request()->has('status')) {
                    $tasks->where('status', request()->status);
                }

                if (request()->has('title')) {
                    $tasks->where('title', 'like', '%' . request()->title . '%');
                }

                $tasks->with(['team', 'assignedUser', 'files:id,task_id,file_path']);
                $tasks->orderBy('created_at', 'desc');

                return $tasks->cursorPaginate(10);
            });
    
            return $this->success(
                $data,
                __('validations.get_success')
            );   
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }

    public function store(TaskStoreRequest $request)
    {
        try {
            $requestData = $request->validated();

            $data = $this->taskService->createTask($requestData);

            return $this->success(
                $data,
                __('validations.created_success'),
                201
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }

    public function update(TaskUpdateRequest $request, $id)
    {
        try {
            $requestData = $request->validated();
            $data = $this->taskService->updateTask($requestData, $id);

            return $this->success(
                $data,
                __('validations.updated_success')
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::find($id);
            
            if (!$task) {
                return $this->error(__("not_found"), null, 404);
            }
            
            $task->delete();
            Cache::forget('tasks');

            return $this->success(
                [],
                __('validations.deleted_success')
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }

    public function files(TaskFileRequest $request, $id)
    {
        try {
            $requestData = $request->validated();
            $data = $this->taskService->files($requestData, $id);

            return $this->success(
                $data,
                __('validations.file_uploaded_success'),
                201
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }
}
