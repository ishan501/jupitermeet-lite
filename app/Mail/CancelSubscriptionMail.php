<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CancelSubscriptionMail extends CustomMailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $content
     */
    public function __construct($subject, $content)
    {
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject . ' | ' . getSetting('APPLICATION_NAME'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.cancel-subscription',
            with: [
                'content' => $this->content,
            ],
        );
    }
}