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

class TaskController extends Controller
{
    /* 
        [ ] TODO validate düzenlemesi
        [ ] TODO service oluşturalım
    */


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

                $tasks->with(['team', 'assignedUser']);
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
            $data = Task::create($requestData);
            event(new TaskAssigned($data->id));
            Cache::forget('tasks');

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
            $data = Task::find($id);
            
            if (!$data) {
                return $this->error('Görev bulunamadı.', null, 404);
            }
            
            $requestData = $request->validated();
            $data->update($requestData);

            if($requestData['status'] == 'completed') {
                event(new TaskCompleted($id));
            }
            Cache::forget('tasks');

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

    public function files($id)
    {
        try {
            $data = Task::find($id)->files;

            return $this->success(
                $data,
                'Dosyalar başarıyla getirildi.'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }
}
