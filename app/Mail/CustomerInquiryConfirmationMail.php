<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerInquiryConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $inquiry;

    public function __construct($inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We have received your inquiry: ' . $this->inquiry->service->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer_confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}