<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\Product;
use App\Models\Operation;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\OrderReceiptMail;
use App\Mail\OrderCancelledMail;

class EmployeeOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-orders']);
    }

    public function index()
    {
        $orders = Order::where('status', 'pending')
            ->with('items.product', 'member')
            ->orderBy('date')
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function complete($id)
    {
        DB::transaction(function () use ($id) {
            $order = Order::with('items.product', 'member')->findOrFail($id);

            if ($order->status !== 'pending') {
                abort(400, 'Encomenda não está em pending.');
            }

            $pdfName = "receipt_{$order->id}.pdf";
            Pdf::loadView('emails.orders.receipt', compact('order'))
                ->save(storage_path("app/public/receipts/{$pdfName}"));

            foreach ($order->items as $item) {
                $prod = $item->product;
                $prod->stock -= $item->quantity;
                $prod->save();
            }

            $order->update([
                'status'      => 'completed',
                'pdf_receipt' => $pdfName,
            ]);

            Mail::to($order->member->email)
                ->send(new OrderReceiptMail($order));
        });

        return back()->with('success', 'Encomenda marcada como completada.');
    }

    public function cancel(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        DB::transaction(function () use ($id, $request) {
            $order = Order::with('member')->findOrFail($id);

            if ($order->status !== 'pending') {
                abort(400, 'Só se podem cancelar encomendas pending.');
            }

            // estorno da encomenda
            Operation::create([
                'card_id'     => $order->member_id,
                'type'        => 'credit',
                'value'       => $order->total,
                'date'        => now()->toDateString(),
                'credit_type' => 'order_cancellation',
                'order_id'    => $order->id,
            ]);
            $order->member->card->increment('balance', $order->total);

            // actualiza estado e razão
            $order->update([
                'status'        => 'canceled',
                'cancel_reason' => $request->reason,
            ]);

            // envia email ao cliente com o motivo
            if ($order->member && $order->member->email) {
                Mail::to($order->member->email)
                    ->send(new OrderCancelledMail($order, $request->reason));
            }
        });

        return back()->with('success', 'Encomenda cancelada e cliente notificado.');
    }
}
