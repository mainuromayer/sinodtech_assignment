<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Sale;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Sale $sale;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice for Sale #' . $this->sale->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
        );
    }
}
