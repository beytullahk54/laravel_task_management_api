<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use App\Mail\TaskAssigned;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $task_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($task_id)
    {
        $this->task_id = $task_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): TaskAssigned
    {
        $task = Task::with('assignedUser')->find($this->task_id);

        return (new TaskAssigned($task))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
