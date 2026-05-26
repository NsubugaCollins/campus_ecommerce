<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PointsUsedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public int $pointsUsed,
        public float $discount,
        public int $remainingPoints
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'You Used ' . $this->pointsUsed . ' Points on Cycle!');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.points_used');
    }
}
