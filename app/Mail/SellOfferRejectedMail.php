<?php

namespace App\Mail;

use App\Models\UserSale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SellOfferRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public UserSale $userSale) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Update on Your Sell Request – Cycle');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.sell_offer_rejected');
    }
}
