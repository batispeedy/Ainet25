<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\Order
     */
    public $order;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdfPath = storage_path('app/public/receipts/' . $this->order->pdf_receipt);

        return $this->subject('Recibo do Pedido #' . $this->order->id)
                    ->view('emails.orders.receipt')
                    ->attach($pdfPath, [
                        'as' => 'Recibo_Pedido_' . $this->order->id . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
