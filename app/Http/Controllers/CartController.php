<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SettingShippingCost;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        $totalItems = 0;

        foreach ($cart as $id => &$item) {
            $unitPrice = $item['unit_price'] ?? $item['price'] ?? 0;
            $discount = $item['discount'] ?? 0;
            $quantity = $item['quantity'] ?? 1;

            $item['unit_price'] = $unitPrice;
            $item['discount']   = $discount;
            $item['quantity']   = $quantity;

            $item['subtotal'] = ($unitPrice - $discount) * $quantity;
            $totalItems += $item['subtotal'];
        }
        unset($item);

        $shippingCost = SettingShippingCost::where('min_value_threshold', '<=', $totalItems)
            ->where('max_value_threshold', '>', $totalItems)
            ->value('shipping_cost') ?? 0;

        $total = $totalItems + $shippingCost;

        return view('cart.index', compact('cart', 'totalItems', 'shippingCost', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = max(1, (int)$request->input('quantity', 1));
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id'         => $product->id,
                'name'       => $product->name,
                'unit_price' => $product->price,
                'discount'   => 0,
                'quantity'   => $quantity,
                'photo'      => $product->photo,
            ];
        }

        
        $cart[$id]['discount'] = ($cart[$id]['quantity'] >= ($product->discount_min_qty ?? PHP_INT_MAX))
            ? $product->discount
            : 0;

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho.');
    }

    public function update(Request $request, $id)
    {
        $quantity = max(1, (int)$request->input('quantity', 1));
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $product = Product::findOrFail($id);
            $unitPrice = $cart[$id]['unit_price'] ?? $cart[$id]['price'] ?? 0;

            $cart[$id]['quantity'] = $quantity;
            $cart[$id]['unit_price'] = $unitPrice;
            $cart[$id]['discount'] = ($quantity >= ($product->discount_min_qty ?? PHP_INT_MAX))
                ? $product->discount
                : 0;

            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Quantidade atualizada.');
        }

        return redirect()->back()->with('error', 'Produto não encontrado no carrinho.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produto removido.');
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->back()->with('success', 'Carrinho limpo.');
    }
}
