<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status', 'assigned_user_id', 'due_date', 'team_id', 'created_by'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
