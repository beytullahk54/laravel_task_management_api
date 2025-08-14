<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskCompletedNotification extends Notification implements ShouldQueue
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
    public function toMail(object $notifiable): MailMessage
    {
        $task = Task::find($this->task_id);

        return (new MailMessage)
            ->subject('Görev tamamlandı: '.$task->title)
            ->greeting('Merhaba '.$notifiable->name)
            ->line($task->title.' görevi tamamlandı.')
            ->action('Görev', url('/tasks/'.$task->id))
            ->line('Görev açıklaması: '.$task->description)
            ->line('Teşekkürler,');
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
