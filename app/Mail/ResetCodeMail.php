<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Reset Password - Daeng Rubik',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-code', // ✅ GANTI INI
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
