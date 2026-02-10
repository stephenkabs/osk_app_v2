<?php

namespace App\Mail;

use App\Models\Property;
use App\Models\PropertyLeaseAgreement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Services\BrevoMailService;

class SendLeaseForSignature extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Property $property,
        public PropertyLeaseAgreement $lease,
        public string $signUrl
    ) {}

    public function build()
    {
        return $this->subject('Lease Agreement – Signature Required')
            ->view('emails.send-lease');
    }

    /**
     * Send this email using Brevo API
     */
    public function sendViaBrevo(string $email): void
    {
        BrevoMailService::send(
            $email,
            'Lease Agreement – Signature Required',
            view('emails.send-lease', [
                'property' => $this->property,
                'lease'    => $this->lease,
                'signUrl'  => $this->signUrl,
            ])->render()
        );
    }
}
