<?php

namespace App\Mail;

use App\Models\UserSale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SellOfferApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public UserSale $userSale) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'We Have an Offer for Your Item – Cycle');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.sell_offer_approved');
    }
}
