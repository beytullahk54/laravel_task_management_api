<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['task_id', 'filename', 'original_name', 'file_path'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
