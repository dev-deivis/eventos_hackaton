<?php

namespace App\Mail;

use App\Models\Equipo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EvaluacionCompletadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $equipo;
    public $evaluacion;

    /**
     * Create a new message instance.
     */
    public function __construct(Equipo $equipo, $evaluacion)
    {
        $this->equipo = $equipo;
        $this->evaluacion = $evaluacion;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⭐ Tu proyecto ha sido evaluado - ' . $this->equipo->nombre,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.evaluacion-completada',
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
