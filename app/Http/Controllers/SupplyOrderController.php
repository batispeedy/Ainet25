<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SupplyOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplyOrderController extends Controller
{
    public function index()
    {
        $orders = SupplyOrder::with('product', 'registeredBy')->latest()->get();
        return view('supply_orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('supply_orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        SupplyOrder::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'status' => 'requested',
            'registered_by_user_id' => Auth::id(),
        ]);

        return redirect()->route('supply_orders.index')->with('success', 'Ordem de reposição criada com sucesso.');
    }

    public function complete(SupplyOrder $order)
    {
        if ($order->status !== 'requested') {
            return redirect()->back()->with('error', 'Esta ordem já foi concluída.');
        }

        $product = $order->product;
        $product->stock += $order->quantity;
        $product->save();

        $order->status = 'completed';
        $order->save();

        return redirect()->route('supply_orders.index')->with('success', 'Ordem marcada como concluída e stock atualizado.');
    }
}
