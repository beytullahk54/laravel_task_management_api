<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TaskFile extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'filename', 'original_name', 'file_path'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
