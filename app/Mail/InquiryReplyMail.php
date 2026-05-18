<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $inquiry;
    public $replyMessage;

    public function __construct($inquiry, $replyMessage)
    {
        $this->inquiry = $inquiry;
        $this->replyMessage = $replyMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Response to your inquiry: ' . ($this->inquiry->service->name ?? 'Alpha Healthcare Inquiry'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inquiry_reply',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}