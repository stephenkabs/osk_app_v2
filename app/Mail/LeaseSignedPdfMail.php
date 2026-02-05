<?php

namespace App\Mail;

use App\Models\Property;
use App\Models\PropertyLeaseAgreement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaseSignedPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Property $property,
        public PropertyLeaseAgreement $agreement
    ) {}

    public function build()
    {
        $this->agreement->load(['tenant','unit','property']);

        $template = $this->property->leaseTemplate;

        $pdf = Pdf::loadView(
            'properties.agreements.pdf',
            [
                'property'        => $this->property,
                'agreement'       => $this->agreement,
                'template'        => $template,
                'logoData'        => null,
                'sigData'         => null,
                'tenantPhotoData' => null,
            ]
        )->setPaper('a4');

        return $this->subject('Your Signed Lease Agreement')
            ->view('emails.lease-signed')
            ->attachData(
                $pdf->output(),
                'Lease-'.$this->agreement->slug.'.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
