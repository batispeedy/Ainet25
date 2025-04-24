<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Operation;
use App\Models\Product;
use App\Models\Card;
use App\Models\SettingShippingCost;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReceiptMail;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $user = Auth::user();

        if (! in_array($user->type, ['member', 'board'])) {
            return redirect()->route('membership.show')
                             ->with('error', 'Tens de pagar a quota para aceder ao checkout.');
        }

        $cart = session('cart', []);
        $totalItems = 0;

        foreach ($cart as &$item) {
            $item['subtotal'] = ($item['unit_price'] - $item['discount']) * $item['quantity'];
            $totalItems += $item['subtotal'];
        }

        $shippingCost = SettingShippingCost::where('min_value_threshold', '<=', $totalItems)
            ->where('max_value_threshold', '>', $totalItems)
            ->value('shipping_cost') ?? 0;

        $total = $totalItems + $shippingCost;

        return view('checkout.index', compact('cart', 'totalItems', 'shippingCost', 'total'));
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'nif'           => 'required|digits:9',
            'address'       => 'required|string|max:255',
            'payment_type'  => 'required|in:Visa,PayPal,MBWAY',
        ]);

        $user = Auth::user();
        $cart = session('cart', []);
        $totalItems = 0;

        foreach ($cart as &$item) {
            $item['subtotal'] = ($item['unit_price'] - $item['discount']) * $item['quantity'];
            $totalItems += $item['subtotal'];
        }

        $shippingCost = SettingShippingCost::where('min_value_threshold', '<=', $totalItems)
            ->where('max_value_threshold', '>', $totalItems)
            ->value('shipping_cost') ?? 0;

        $total = $totalItems + $shippingCost;

        DB::transaction(function () use ($user, $cart, $totalItems, $shippingCost, $total, $request) {
            // Carrega o cartão do utilizador
            $card = Card::findOrFail($user->id);

            if ($card->balance < $total) {
                // Força rollback e mostra erro
                abort(400, 'Saldo insuficiente no cartão.');
            }

            // Debita o saldo do cartão
            $card->decrement('balance', $total);

            // Regista a operação de débito
            Operation::create([
                'card_id'    => $card->id,
                'type'       => 'debit',
                'debit_type' => 'order',
                'value'      => $total,
                'date'       => now()->toDateString(),
            ]);

            // Cria a encomenda
            $order = Order::create([
                'member_id'        => $user->id,
                'status'           => 'pending',
                'nif'              => $request->nif,
                'delivery_address' => $request->address,
                'total_items'      => $totalItems,
                'shipping_cost'    => $shippingCost,
                'total'            => $total,
                'date'             => now()->toDateString(),
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount'   => $item['discount'],
                    'subtotal'   => $item['subtotal'],
                ]);
                Product::where('id', $item['id'])
                       ->decrement('stock', $item['quantity']);
            }

            // PDF e envio de e-mail
            $pdfName = 'receipt_'.$order->id.'.pdf';
            Pdf::loadView('emails.orders.receipt', compact('order'))
               ->save(storage_path('app/public/receipts/'.$pdfName));
            $order->update(['pdf_receipt' => $pdfName]);

            Mail::to($user->email)->send(new OrderReceiptMail($order));
        });

        session()->forget('cart');

        return redirect()->route('dashboard')
                         ->with('success', 'Encomenda registada com sucesso!');
    }
}
