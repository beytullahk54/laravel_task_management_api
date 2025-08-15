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

    /**
     * Task modeli ile veri ekleme işlemi yapılır. Ardından TaskAssigned eventi tetiklenir.
     * Her işlem sonrası cache temizlenir. Verilerin güncelliği sağlanır.
     */

    public function createTask($requestData)
    {
        //Task oluşturulduktan sonra task_assigned eventi tetiklenir.
        $data = Task::create($requestData);
        event(new TaskAssigned($data->id));
        Cache::forget('tasks');

        return $data;
    }
    
    public function updateTask($requestData, $id)
    { 
        $data = Task::find($id);
            
        if (!$data) {
            return $this->error(__("not_found"), null, 404);
        }
        
        $data->update($requestData);

        if($requestData['status'] == 'completed') {
            event(new TaskCompleted($id));
        }
        Cache::forget('tasks');

        return $data;
    }

    public function files($requestData, $id)
    {
        /*
        * Task dosya yükleme işlemi taska özel tabloda tutulmaktadır. 
        * Imagine gibi bir tablo oluşturularak istendiği takdirde morpMany ilişkisi de kullanılabilir
        * Dosya tarih_saat_dosya_adı şeklinde oluşturulmaktadır.
        * Her seferinde bir dosya tutulmaktadır. Burası kaldırılırak istendiği kadar dosya kaydedilebilir.
        * Cache silinerek tasklarda dosyanın güncel görünmesi sağlanır.
        */

        $task = Task::find($id);
        $task->load('files');
        
        $task->files->each(function ($file) use ($task) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
            $task->files()->delete($file->id);
        });

        $file = $requestData['file'];
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
