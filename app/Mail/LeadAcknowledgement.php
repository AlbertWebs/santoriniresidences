<?php

namespace App\Mail;

use App\Models\Lead;
use App\Support\LeadFormTypes;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class LeadAcknowledgement extends Mailable
{
    use Queueable;

    public function __construct(public Lead $lead)
    {
    }

    public function envelope(): Envelope
    {
        $visit = in_array($this->lead->form_type, ['book-visit', 'schedule-visit'], true);

        return new Envelope(
            subject: $visit ? 'We have received your visit request' : 'Thank you for contacting Santorini Residences',
            replyTo: [new Address('hello@santoriniresidences.com', 'Santorini Residences')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leads.acknowledgement',
            with: [
                'lead' => $this->lead,
                'requestLabel' => LeadFormTypes::label($this->lead->form_type),
            ],
        );
    }
}
