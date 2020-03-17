<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpensesNotification extends Notification
{
    use Queueable;
    protected $expenses;
    protected $user;
    public function __construct($expenses,$user)
    {
        $this->expenses = $expenses;
        $this->user = $user;
    }
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }
    public function toDatabase($notifiable)
    {
        return [
            'sender_id' => $this->user->id,
            'sender_name' => $this->user->name,
            'expenses' => $this->expenses
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'sender_id' => $this->user->id,
            'sender_name' => $this->user->name,
            'expenses' => $this->expenses
        ]);
    }
}
