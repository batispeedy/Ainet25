<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Category;

class StatsController extends Controller
{
    public function index()
    {
        // 1) Vendas por mês (eixo X: YYYY-MM)
        $salesByMonth = Order::selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(total) as total")
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // 2) Vendas por categoria
        $rawCats = DB::table('items_orders')
            ->join('products',    'items_orders.product_id', '=', 'products.id')
            ->join('orders',      'items_orders.order_id',   '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select('products.category_id', DB::raw('SUM(items_orders.subtotal) as total'))
            ->groupBy('products.category_id')
            ->pluck('total', 'products.category_id');

        $salesByCategory = [];
        foreach ($rawCats as $catId => $total) {
            $cat = Category::find($catId);
            $salesByCategory[$cat->name ?? '—'] = (float) $total;
        }

        // 3) Vendas por produto
        $salesByProductData = DB::table('items_orders')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('orders',   'items_orders.order_id',   '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select('products.name', DB::raw('SUM(items_orders.subtotal) as total'))
            ->groupBy('products.name')
            ->pluck('total', 'products.name')
            ->toArray();

        // 4) Vendas por utilizador
        $salesByUserData = DB::table('orders')
            ->join('users', 'orders.member_id', '=', 'users.id')
            ->where('orders.status', 'completed')
            ->select('users.name', DB::raw('SUM(orders.total) as total'))
            ->groupBy('users.name')
            ->pluck('total', 'users.name')
            ->toArray();

        return view('stats.index', compact(
            'salesByMonth',
            'salesByCategory',
            'salesByProductData',
            'salesByUserData'
        ));
    }

    public function personal()
    {
        $userId = Auth::id();

        // 1) As minhas vendas por mês
        $salesByMonth = Order::where('member_id', $userId)
            ->where('status', 'completed')
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(total) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // 2) As minhas vendas por categoria
        $rawCats = DB::table('items_orders')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('orders',   'items_orders.order_id',   '=', 'orders.id')
            ->where('orders.status',    'completed')
            ->where('orders.member_id', $userId)
            ->select('products.category_id', DB::raw('SUM(items_orders.subtotal) as total'))
            ->groupBy('products.category_id')
            ->pluck('total', 'products.category_id');

        $salesByCategory = [];
        foreach ($rawCats as $catId => $total) {
            $cat = Category::find($catId);
            $salesByCategory[$cat->name ?? '—'] = (float) $total;
        }

        // 3) As minhas vendas por produto
        $salesByProductData = DB::table('items_orders')
            ->join('products', 'items_orders.product_id', '=', 'products.id')
            ->join('orders',   'items_orders.order_id',   '=', 'orders.id')
            ->where('orders.status',    'completed')
            ->where('orders.member_id', $userId)
            ->select('products.name', DB::raw('SUM(items_orders.subtotal) as total'))
            ->groupBy('products.name')
            ->pluck('total', 'products.name')
            ->toArray();

        return view('stats.personal', compact(
            'salesByMonth',
            'salesByCategory',
            'salesByProductData'
        ));
    }
}