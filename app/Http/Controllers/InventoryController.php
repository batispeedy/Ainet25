<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\SupplyOrder;
use App\Models\StockAdjustment;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-inventory');
    }

    /**
     * Display all products and their stock levels.
     */
    public function index()
    {
        $products = Product::orderBy('name')->paginate(15);
        return view('inventory.index', compact('products'));
    }

    /**
     * Display products below the lower stock limit.
     */
    public function lowStock()
    {
        $products = Product::whereColumn('stock', '<', 'stock_lower_limit')
            ->orderBy('stock')
            ->paginate(15);
        return view('inventory.low', compact('products'));
    }

    /**
     * Display out-of-stock products.
     */
    public function outOfStock()
    {
        $products = Product::where('stock', '<=', 0)
            ->orderBy('name')
            ->paginate(15);
        return view('inventory.out', compact('products'));
    }

    /**
     * Create a manual supply order.
     */
    public function createSupplyOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        SupplyOrder::create([
            'product_id'             => $request->product_id,
            'registered_by_user_id'  => Auth::id(),
            'status'                 => 'requested',
            'quantity'               => $request->quantity,
        ]);

        return back()->with('success', 'Supply order criada.');
    }

    /**
     * Complete a supply order and update stock.
     */
    public function completeSupplyOrder($id)
    {
        $order = SupplyOrder::findOrFail($id);
        if ($order->status !== 'requested') {
            return back()->with('error', 'Ordem já completada.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'completed']);
            Product::where('id', $order->product_id)
                ->increment('stock', $order->quantity);
        });

        return back()->with('success', 'Supply order completada e stock atualizado.');
    }

    /**
     * Manually adjust stock for a product.
     */
    public function adjustStock(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer']);
        $product = Product::findOrFail($id);
        $delta = $request->quantity - $product->stock;

        DB::transaction(function () use ($product, $delta) {
            $product->update(['stock' => $product->stock + $delta]);
            StockAdjustment::create([
                'product_id'             => $product->id,
                'registered_by_user_id'  => Auth::id(),
                'quantity_changed'       => $delta,
            ]);
        });

        return back()->with('success', 'Stock ajustado manualmente.');
    }
}
