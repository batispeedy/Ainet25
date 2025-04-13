<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'nif' => 'required|string|max:20',
            'address' => 'required|string|max:255',
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

        // Calcula subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Calcula portes
        $shipping = $subtotal <= 50 ? 10 : ($subtotal <= 100 ? 5 : 0);
        $total = $subtotal + $shipping;

        // Verifica saldo do cartão virtual
        $card = Card::find($user->id); // Ou $user->card, se tiveres o relacionamento
        if (!$card || $card->balance < $total) {
            return redirect()->route('cart.index')->with('error', 'Saldo insuficiente no cartão virtual.');
        }

        // Cria a encomenda
        $order = Order::create([
            'user_id' => $user->id,
            'nif' => $request->nif,
            'delivery_address' => $request->address,
            'status' => 'preparing',
            'total_price' => $total,
            'shipping_cost' => $shipping,
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
            'user_id' => $user->id,
            'type' => 'debit',
            'value' => $total,
            'payment_type' => 'virtual_card',
        ]);

        // Limpa carrinho
        session()->forget('cart');

        return redirect()->route('store.index')->with('success', 'Encomenda efetuada com sucesso!');
    }
}
