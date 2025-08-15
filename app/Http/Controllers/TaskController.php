<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Task;
use App\Http\Request\Task\TaskStoreRequest;
use App\Http\Request\Task\TaskUpdateRequest;
use App\Events\TaskAssigned;
use App\Events\TaskCompleted;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
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
                'Görevler başarıyla getirildi.'
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
                'Görev başarıyla oluşturuldu.',
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
                'Görev başarıyla güncellendi.'
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
                return $this->error('Görev bulunamadı.', null, 404);
            }
            
            $task->delete();
            Cache::forget('tasks');

            return $this->success(
                [],
                'Görev başarıyla silindi.'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }

    public function files(Request $request, $id)
    {
        try {
            $data = $this->taskService->files($request, $id);

            return $this->success(
                $data,
                'Dosya başarıyla oluşturuldu.',
                201
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }
}
