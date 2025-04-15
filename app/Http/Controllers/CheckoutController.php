<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Card;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function confirm(Request $request)
{ 
    logger('⚠️ CHECKOUT CHEGOU AQUI!');
    $request->validate([
        'nif' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'payment_type' => 'required|in:Visa,PayPal,MBWAY',
        'card_number' => 'required_if:payment_type,Visa',
        'cvc_code' => 'required_if:payment_type,Visa',
        'email_address' => 'required_if:payment_type,PayPal|email',
        'phone_number' => 'required_if:payment_type,MBWAY|regex:/^9\d{8}$/',
    ]);

        $user = Auth::user();

        // Verifica se é membro
        if ($user->type !== 'member') {
            return redirect()->back()->with('error', 'Apenas membros do clube podem finalizar compras.');
        }

        // Obtem o carrinho
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'O carrinho está vazio.');
        }

        // Calcula subtotal e total de itens
        $subtotal = 0;
        $totalItems = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalItems += $item['quantity'];
        }

        // Calcula portes
        $shipping = $subtotal <= 50 ? 10 : ($subtotal <= 100 ? 5 : 0);
        $total = $subtotal + $shipping;

        // Verifica saldo do cartão virtual
        $card = $user->card;
        if (!$card || $card->balance < $total) {
            return redirect()->route('cart.index')->with('error', 'Saldo insuficiente no cartão virtual.');
        }

        // Cria a encomenda
        $order = Order::create([
            'member_id' => $user->id,
            'nif' => $request->nif,
            'delivery_address' => $request->address,
            'status' => 'preparing',
            'shipping_cost' => $shipping,
            'total_price' => $total,
            'total_items' => $totalItems,
            'date' => Carbon::now()->toDateString(),
        ]);

        // Cria os itens da encomenda
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ]);
        }

        // Debita o cartão
        $card->balance -= $total;
        $card->save();

        // Regista a operação
        Operation::create([
            'card_id' => $card->id,
            'type' => 'debit',
            'value' => $total,
            'date' => Carbon::now()->toDateString(),
            'debit_type' => 'order',
            'order_id' => $order->id,
        ]);

        // Limpa carrinho
        session()->forget('cart');

        return redirect()->route('store.index')->with('success', 'Encomenda efetuada com sucesso!');
    }
}
