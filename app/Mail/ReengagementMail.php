<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Customer;

class ReengagementMail extends Mailable
{
    use Queueable, SerializesModels;

    public Customer $customer;
    public string $promoMessage;

    public function __construct(Customer $customer, string $promoMessage)
    {
        $this->customer = $customer;
        $this->promoMessage = $promoMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We miss you, ' . $this->customer->name . '!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reengagement',
        );
    }
}
