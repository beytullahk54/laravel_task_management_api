<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Task;
use App\Http\Request\Task\TaskStoreRequest;
use App\Http\Request\Task\TaskUpdateRequest;
class TaskController extends Controller
{
    public function index()
    {
        try {
            $data = Cache::remember('tasks', 60, function () {
                return Task::with(['team', 'assignedUser'])->paginate(10);
            });
    
            return $this->success(
                $data,
                'Görevler başarıyla getirildi.'
            );   
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function store(TaskStoreRequest $request)
    {
        try {
            $data = Task::create($request->validated());
            Cache::forget('tasks');

            return $this->success(
                $data,
                'Görev başarıyla oluşturuldu.'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function update(TaskUpdateRequest $request, $id)
    {
        try {
            $data = Task::find($id);
            
            if (!$data) {
                return $this->error('Görev bulunamadı.', null, 404);
            }
            
            $data->update($request->validated());
            Cache::forget('tasks');

            return $this->success(
                $data,
                'Görev başarıyla güncellendi.'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
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
            return $this->error($e->getMessage());
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
            return $this->error($e->getMessage());
        }
    }
}
