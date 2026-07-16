<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBookingAdminAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New booking — ' . $this->appointment->customer_name
                . ' (' . $this->appointment->reference . ')',
        );
    }

    public function content(): Content
    {
        $base = rtrim((string) config('emcey.frontend_url'), '/');

        return new Content(
            markdown: 'emails.new-booking-admin',
            with: [
                'appointment' => $this->appointment,
                'adminUrl' => $base . '/admin/appointments',
            ],
        );
    }
}
