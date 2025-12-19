<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminTwoFactorCodeNotification extends Notification
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
            ->subject(config('app.name') . ' admin verification code')
            ->greeting('Hello ' . ($notifiable->name ?? 'Admin') . ',')
            ->line('Use the verification code below to complete your secure login.')
            ->line('Authentication code: **' . $this->code . '**')
            ->line("The code expires in {$this->minutes} minutes.")
            ->line('If you did not request this sign-in, please notify the platform owner immediately.');
    }
}
