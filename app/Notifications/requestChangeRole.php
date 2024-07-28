<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\Permission\Models\Role;

class requestChangeRole extends Notification
{
    use Queueable;

    public $role;
    public $user;

    public function __construct($role, $user)
    {
        $this->role = Role::find($role);
        $this->user = $user;
        $this->afterCommit();
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('You have requested to change your role '. $this->role->name .'.')
                    ->line('The user '. $this->user->name.' '. $this->user->lastname .'.')
                    ->line('Email '. $this->user->email .'.');
    }

    public function toArray(object $notifiable): array
    {
        return [
              
        ];
    }
}
