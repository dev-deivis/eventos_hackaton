<?php

namespace App\Mail;

use App\Models\Equipo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudUnionEquipoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $equipo;
    public $solicitante;
    public $lider;

    /**
     * Create a new message instance.
     */
    public function __construct(Equipo $equipo, User $solicitante, User $lider)
    {
        $this->equipo = $equipo;
        $this->solicitante = $solicitante;
        $this->lider = $lider;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '👥 Nueva solicitud para unirse a ' . $this->equipo->nombre,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.solicitud-union-equipo',
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
