<?php

namespace App\Mail;

use App\PropertyManagement\Models\RentPayment;
use App\PropertyManagement\Support\ClaimMailSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    /**
     * Create a new message instance.
     */
    public function __construct(RentPayment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $clientName = $this->payment->contract->client->name ?? 'العميل';
        $unitNumber = $this->payment->contract->unit->unit_number ?? 'N/A';
        $buildingName = $this->payment->contract->building->name ?? 'غير محدد';

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                config('services.resend.from_email', 'info@alzeer-holding.com'),
                config('services.resend.from_name', 'Alzeer Holding')
            ),
            cc: ClaimMailSettings::ccEmails(),
            bcc: config('mail.customer_bcc', []),
            subject: "مطالبة بسداد قسط الإيجار - عقد رقم {$this->payment->contract->contract_number}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_request',
            with: [
                'payment' => $this->payment,
                'claimBankIban' => ClaimMailSettings::bankIban(),
            ],
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
