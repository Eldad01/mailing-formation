<?php

namespace App\Mail;

use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MessageParticipant extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<int, array{path: string, name: string}>  $piecesJointes  Fichiers (disque "local") à joindre.
     */
    public function __construct(
        public Inscription $inscription,
        public string $objet,
        public string $corps,
        public array $piecesJointes = [],
    ) {
        $this->inscription->loadMissing('formation');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->objet,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.participants.message',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return array_map(
            fn (array $piece) => Attachment::fromStorageDisk('local', $piece['path'])->as($piece['name']),
            $this->piecesJointes,
        );
    }
}
