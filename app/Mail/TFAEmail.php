<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class TFAEmail extends CustomMailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $content)
    {
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): \Illuminate\Mail\Mailables\Envelope
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: $this->subject . ' | ' . getSetting('APPLICATION_NAME'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): \Illuminate\Mail\Mailables\Content
    {
        return new \Illuminate\Mail\Mailables\Content(
            markdown: 'mail.tfa-code',
            with: [
                'content' => $this->content,
            ],
        );
    }
}