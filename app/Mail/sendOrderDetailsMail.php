<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendOrderDetailsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $maildata;
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Order has been placed. Your order number will be '.$this->maildata['order_number'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'customer.checkout.email_order_placed',
            with: [
                'maildata' => $this->maildata,
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

    // ------------------------------------------------
    // USAGE NOTE: To send this email to the customer's email dynamically,
    // use the following wherever you handle orders (e.g., in your controller):
    //
    // $maildata = [
    //     'order_number' => $order->order_number,
    //     'customer_name' => $order->customer_name,
    //     'items' => $order->items,
    //     'total' => $order->total_price,
    // ];
    //
    // Mail::to($order->customer_email)->send(new sendOrderDetailsMail($maildata));
    //
    // Optionally, you can CC or BCC yourself/admin:
    // Mail::to($order->customer_email)
    //     ->cc('halolucas61@gmail.com')
    //     ->send(new sendOrderDetailsMail($maildata));
    // ------------------------------------------------
}
