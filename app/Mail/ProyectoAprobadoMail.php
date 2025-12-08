<?php

namespace App\Mail;

use App\Models\Equipo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProyectoAprobadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $equipo;
    public $proyecto;

    public function __construct(Equipo $equipo, $proyecto)
    {
        $this->equipo = $equipo;
        $this->proyecto = $proyecto;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ ¡Tu proyecto ha sido aprobado! - ' . $this->equipo->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proyecto-aprobado',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
