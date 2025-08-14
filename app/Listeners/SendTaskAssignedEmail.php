<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Events\TaskAssigned;
use App\Mail\TaskAssigned as TaskAssignedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Task;
use App\Events\TaskAssigned as TaskAssignedEvent;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;

class SendTaskAssignedEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }   

    /**
     * Handle the event.
     */
    public function handle(TaskAssigned $event): void
    {
        $task = Task::find($event->task_id);
        Notification::send(User::find($task->assigned_user_id), new TaskAssignedNotification($event->task_id));
    }
}
