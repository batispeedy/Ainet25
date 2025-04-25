<?php
// app/Mail/OrderCancelledMail.php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $reason;

    public function __construct(Order $order, string $reason)
    {
        $this->order  = $order;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this
            ->subject("Encomenda #{$this->order->id} Cancelada")
            ->markdown('emails.orders.cancelled')
            ->with([
                'order'  => $this->order,
                'reason' => $this->reason,
            ]);
    }
}