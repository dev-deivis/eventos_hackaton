<?php

namespace App\Mail;

use App\Models\Equipo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AceptadoEnEquipoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $equipo;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Equipo $equipo, User $user)
    {
        $this->equipo = $equipo;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 ¡Te aceptaron en ' . $this->equipo->nombre . '!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.aceptado-en-equipo',
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
