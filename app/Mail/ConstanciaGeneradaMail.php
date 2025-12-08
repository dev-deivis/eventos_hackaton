<?php

namespace App\Mail;

use App\Models\Constancia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConstanciaGeneradaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $constancia;

    public function __construct(Constancia $constancia)
    {
        $this->constancia = $constancia;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🏆 Tu constancia está lista - ' . $this->constancia->evento->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.constancia-generada',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
