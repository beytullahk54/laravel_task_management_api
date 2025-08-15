<?php

namespace App\Services;

use App\Models\Task;
use App\Events\TaskAssigned;
use Illuminate\Support\Facades\Cache;
use App\Traits\ApiResponse;
use App\Events\TaskCompleted;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TaskService
{
    use ApiResponse;

    public function createTask($requestData)
    {
        $data = Task::create($requestData);
        event(new TaskAssigned($data->id));
        Cache::forget('tasks');

        return $data;
    }
    
    public function updateTask($requestData, $id)
    { 
        $data = Task::find($id);
            
        if (!$data) {
            return $this->error('Görev bulunamadı.', null, 404);
        }
        
        $data->update($requestData);

        if($requestData['status'] == 'completed') {
            event(new TaskCompleted($id));
        }
        Cache::forget('tasks');

        return $data;
    }

    public function files($request, $id)
    {
        $task = Task::find($id);
        $task->load('files');
        
        $task->files->each(function ($file) use ($task) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
            $task->files()->delete($file->id);
        });

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $timestamp = Carbon::now()->timestamp; 
        $filename = Carbon::now()->format('Ymd_His').'_'.$timestamp.'.'.$extension; 

        $path = $file->storeAs('files/'.$task->id, $filename, 'public');

        $task->files()->create([
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
        ]);

        Cache::forget('tasks');

        return $task;
    }
}
