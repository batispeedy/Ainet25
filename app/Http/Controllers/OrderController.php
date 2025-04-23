<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Operation;
use App\Models\Card;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Lista as encomendas do utilizador autenticado.
     */
    public function index()
    {
        $user   = Auth::user();
        $orders = Order::where('member_id', $user->id)
                        ->orderByDesc('date')
                        ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Mostra detalhes de uma encomenda.
     */
    public function show(Order $order)
    {
        $user = Auth::user();

        // Só permite ver se for mesmo do utilizador
        if ($order->member_id !== $user->id) {
            abort(403, 'Não autorizado.');
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Cancela uma encomenda pendente e faz o reembolso.
     */
    public function cancel(Order $order)
    {
        $user = Auth::user();

        // Só o dono da encomenda pode cancelar
        if ($order->member_id !== $user->id) {
            abort(403, 'Não autorizado a cancelar esta encomenda.');
        }

        // Só encomendas pendentes
        if ($order->status !== 'pending') {
            return redirect()
                ->route('orders.index')
                ->with('error', 'Só podes cancelar encomendas pendentes.');
        }

        // Transação atómica para status + reembolso
        DB::transaction(function () use ($order) {
            // 1) Reembolso no cartão
            $card = Card::find($order->member_id);
            Operation::create([
                'card_id'        => $card->id,
                'type'           => 'credit',
                'value'          => $order->total,
                'date'           => now()->toDateString(),
                'credit_type'    => 'order_cancellation',
                'order_id'       => $order->id,
            ]);
            $card->balance += $order->total;
            $card->save();

            // 2) Atualiza o estado da encomenda
            $order->status        = 'canceled';
            $order->cancel_reason = 'Cancelado pelo utilizador';
            $order->save();
        });

        return redirect()
            ->route('orders.index')
            ->with('success', 'Encomenda cancelada com sucesso.');
    }
}