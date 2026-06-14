<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceInquiryMail extends Mailable
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
            subject: 'New Service Inquiry: ' . ($this->inquiry->service->name ?? 'General Enquiry'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.service_inquiry',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}