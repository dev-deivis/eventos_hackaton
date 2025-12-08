<?php

namespace App\Mail;

use App\Models\Equipo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudAceptadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $equipo;
    public $participante;

    /**
     * Create a new message instance.
     */
    public function __construct(Equipo $equipo, User $participante)
    {
        $this->equipo = $equipo;
        $this->participante = $participante;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ ¡Has sido aceptado en el equipo ' . $this->equipo->nombre . '!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.solicitud-aceptada',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
