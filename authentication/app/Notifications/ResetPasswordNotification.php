<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordNotification extends BaseResetPassword implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public array $backoff = [10, 60];
    public function toMail($notifiable): MailMessage
    {
        $minutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        return (new MailMessage)
            ->subject('Reset your password')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('We received a request to reset the password for your account.')
            ->action('Choose a new password', $this->resetUrl($notifiable))
            ->line("This link will expire in {$minutes} minutes.")
            ->line('If you did not request this, you can safely ignore this email.');
    }
}