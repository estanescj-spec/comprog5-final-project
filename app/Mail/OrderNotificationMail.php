<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $subjectLine,
        public string $messageLine
    ) {
        $this->order->loadMissing(['user', 'items.variant.product', 'payment']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.notification',
            with: [
                'order' => $this->order,
                'messageLine' => $this->messageLine,
            ]
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(function () {
                return Pdf::loadView('pdf.order-receipt', [
                    'order' => $this->order,
                ])->setOption('defaultFont', 'DejaVu Sans')->output();
            }, 'receipt-order-' . $this->order->id . '-' . now()->format('YmdHis') . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
