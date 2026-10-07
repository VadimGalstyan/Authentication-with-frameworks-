<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends BaseVerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Confirm your email address')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('Thanks for creating an account. Please confirm your email address to get started.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('This link will expire soon, so please use it right away.')
            ->line('If you did not create an account, no further action is required.');
    }
}