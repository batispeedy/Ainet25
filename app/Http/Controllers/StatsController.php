<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class StatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('manage-settings');

        $salesByMonth = DB::table('orders')
            ->selectRaw("strftime('%Y-%m', date) as month, SUM(total) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $salesByCategory = DB::table('items_orders')
            ->join('orders', 'items_orders.order_id', '=', 'orders.id')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, SUM(items_orders.subtotal) as total')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->pluck('total', 'category');

        $salesByProduct = DB::table('items_orders')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->selectRaw('products.name as product, SUM(items_orders.subtotal) as total')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->pluck('total', 'product');

        $salesByMember = DB::table('orders')
            ->join('users', 'orders.member_id', '=', 'users.id')
            ->selectRaw('users.name as member, SUM(orders.total) as total')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total')
            ->pluck('total', 'member');

        return view('stats.index', compact(
            'salesByMonth',
            'salesByCategory',
            'salesByProduct',
            'salesByMember'
        ));
    }

    public function personal()
    {
        $userId = Auth::id();


        $salesByMonth = DB::table('orders')
            ->selectRaw("strftime('%Y-%m', date) as month, SUM(total) as total")
            ->where('member_id', $userId)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');


        $salesByCategory = DB::table('items_orders')
            ->join('orders', 'items_orders.order_id', '=', 'orders.id')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, SUM(items_orders.subtotal) as total')
            ->where('orders.member_id', $userId)
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->pluck('total', 'category');

        return view('stats.personal', compact(
            'salesByMonth',
            'salesByCategory'
        ));
    }
}
