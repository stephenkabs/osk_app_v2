<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Services\BrevoMailService;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];   // keep mail channel
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ], false));

        $html = view('emails.reset-password', [
            'url' => $url,
            'user' => $notifiable
        ])->render();

        BrevoMailService::send(
            $notifiable->email,
            'Reset Password Notification',
            $html
        );

        return null; // prevent Laravel SMTP sending
    }
}
