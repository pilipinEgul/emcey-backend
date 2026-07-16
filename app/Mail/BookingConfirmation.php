<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your booking — ' . $this->appointment->reference,
        );
    }

    public function content(): Content
    {
        $base = rtrim((string) config('emcey.frontend_url'), '/');

        return new Content(
            markdown: 'emails.booking-confirmation',
            with: [
                'appointment' => $this->appointment,
                'trackUrl' => $base . '/track?ref=' . urlencode($this->appointment->reference),
            ],
        );
    }
}
