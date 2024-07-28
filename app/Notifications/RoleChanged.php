<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoleChanged extends Notification
{
    use Queueable;

    public $role;

    public $status; 
    public function __construct($role, $status = 'Approved')
    {
        $this->role = $role;
        $this->status = $status;
        $this->afterCommit();
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your role has been '. $this->status)
                    ->line('Role Actual: '. $this->role->name .'.')
                    ->action('View Details', env('APP_URL').'/login')
                    ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
              
        ];
    }

}
