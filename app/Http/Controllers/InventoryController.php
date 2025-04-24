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

    public function index()
    {
        $products = Product::orderBy('name')->paginate(15);
        return view('inventory.index', compact('products'));
    }

    public function lowStock()
    {
        $products = Product::whereColumn('stock', '<', 'stock_lower_limit')
            ->orderBy('stock')
            ->paginate(15);
        return view('inventory.low', compact('products'));
    }

    public function outOfStock()
    {
        $products = Product::where('stock', '<=', 0)
            ->orderBy('name')
            ->paginate(15);
        return view('inventory.out', compact('products'));
    }

    public function createSupplyOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $quantity = $request->filled('quantity')
            ? (int) $request->quantity
            : max(0, $product->stock_upper_limit - $product->stock);

        if ($quantity <= 0) {
            return back()->with('error', "Não é necessário encomendar stock para “{$product->name}”.");
        }

        SupplyOrder::create([
            'product_id'            => $product->id,
            'registered_by_user_id' => Auth::id(),
            'status'                => 'requested',
            'quantity'              => $quantity,
        ]);

        return back()->with('success', "Supply order criada para “{$product->name}” (quantidade: {$quantity}).");
    }

    public function completeSupplyOrder($id)
    {
        $order = SupplyOrder::findOrFail($id);

        if ($order->status !== 'requested') {
            return back()->with('error', 'Esta ordem já foi completada.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'completed']);

            Product::where('id', $order->product_id)
                ->increment('stock', $order->quantity);
        });

        return back()->with('success', 'Supply order completada e stock atualizado.');
    }

    public function adjustStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $newQuantity = (int) $request->input('quantity');
        $delta       = $newQuantity - $product->stock;

        $product->stock = $newQuantity;
        $product->save();

        StockAdjustment::create([
            'product_id'            => $product->id,
            'registered_by_user_id' => Auth::id(),
            'quantity_changed'      => $delta,
        ]);

        return back()->with('success', 'Stock ajustado com sucesso.');
    }
}
