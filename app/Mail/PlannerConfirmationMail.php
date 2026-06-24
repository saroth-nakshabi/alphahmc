<?php

namespace App\Mail;

use App\Models\ProjectPlannerSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlannerConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public ProjectPlannerSession $session;
    public array $cards;
    public array $brief;

    public function __construct(ProjectPlannerSession $session, array $cards, array $brief)
    {
        $this->session = $session;
        $this->cards   = $cards;
        $this->brief   = $brief;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Healthcare Project Plan is Ready — Alpha Health Group',
            replyTo: [new \Illuminate\Mail\Mailables\Address('info@alphatsm.com', 'Alpha Health Group')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.planner_confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
