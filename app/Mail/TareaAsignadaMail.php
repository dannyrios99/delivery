<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TareaAsignadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tarea;
    public $user;

    public function __construct($tarea, $user)
    {
        $this->tarea = $tarea;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('📌 Nueva tarea asignada')
            ->markdown('emails.tarea.asignada');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tarea Asignada Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tarea.asignada',
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
