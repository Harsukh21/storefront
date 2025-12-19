<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserTwoFactorCodeNotification extends Notification
{
    use Queueable;

    public function __construct(protected string $code, protected int $minutes)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your ' . config('app.name') . ' verification code')
            ->greeting('Hi ' . ($notifiable->name ?? 'there') . ',')
            ->line('Use the verification code below to finish signing in.')
            ->line('Authentication code: **' . $this->code . '**')
            ->line("This code expires in {$this->minutes} minutes.")
            ->line('If you did not request this code, please ignore this email.');
    }
}
