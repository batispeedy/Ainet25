<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Order;

class StatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Painel de estatísticas gerais (board).
     */
    public function index()
    {
        // Vendas mensais (apenas encomendas completadas)
        $salesByMonth = Order::select(
                DB::raw("strftime('%Y-%m', date) as month"),
                DB::raw('SUM(total_items + shipping_costs) as total')
            )
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('total', 'month')
            ->toArray();

        // Novos membros totais
        $newMembersCount = User::where('type', 'member')->count();

        // Encomendas por status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as cnt'))
            ->groupBy('status')
            ->pluck('cnt','status')
            ->toArray();

        return view('stats.index', compact(
            'salesByMonth',
            'newMembersCount',
            'ordersByStatus'
        ));
    }

    /**
     * Estatísticas pessoais do utilizador logado.
     */
    public function personal()
    {
        $user = Auth::user();

        $orders = Order::where('member_id', $user->id)
                       ->where('status', 'completed');

        $totalSpent     = $orders->sum(DB::raw('total_items + shipping_costs'));
        $ordersCount    = $orders->count();
        $averagePerOrder = $ordersCount
            ? round($totalSpent / $ordersCount, 2)
            : 0;

        return view('stats.personal', compact(
            'totalSpent',
            'ordersCount',
            'averagePerOrder'
        ));
    }
}
