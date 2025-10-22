<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentDeletedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $notificationData;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($user, array $notificationData)
    {
        $this->user = $user;
        $this->notificationData = $notificationData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Research Document Deleted - ' . $this->notificationData['document_name'] . ' - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Check if view exists - corrected to match your folder name
        if (!view()->exists('email.document-deleted')) {
            \Log::error('Email view not found: email.document-deleted');
        }
        
        return new Content(
            view: 'email.document-deleted', // This is correct for your 'email' folder
            with: [
                'user' => $this->user,
                'data' => $this->notificationData,
                'appName' => config('app.name'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}