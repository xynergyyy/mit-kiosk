<?php

namespace App\Mail;

use App\Models\AppointmentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AppointmentRequest $appointment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your appointment has been scheduled');
    }

    public function content(): Content
    {
        $this->appointment->loadMissing('targetUser');
        return new Content(view: 'emails.appointment_scheduled');
    }
}
