<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskCompletedNotification;
use App\Events\TaskCompleted;
use App\Models\Team;

class SendTaskCompletedEmail
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
    public function handle(TaskCompleted $event): void
    {
        $task = Task::find($event->task_id);
        $team = Team::find($task->team_id);
        Notification::send(User::find($team->owner_id), new TaskCompletedNotification($event->task_id));
    }
}
