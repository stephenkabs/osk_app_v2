<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Services\BrevoMailService;

class TenantApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Property $property
    ) {}

    public function build()
    {
        return $this->subject('Application Received – Under Review')
            ->view('emails.tenant-application-received');
    }

    /**
     * Send via Brevo API
     */
    public function sendViaBrevo(string $email): void
    {
        BrevoMailService::send(
            $email,
            'Application Received – Under Review',
            view('emails.tenant-application-received', [
                'user'     => $this->user,
                'property' => $this->property,
            ])->render()
        );
    }
}
