@extends('layouts.app')

@section('title', 'Carrinho de Compras')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-3xl font-bold mb-6">Carrinho</h1>

    @if(!isset($cart) || count($cart) === 0)
    <p class="text-gray-600">O carrinho está vazio.</p>
    @else
    <table class="w-full mb-6">
        <thead>
            <tr class="text-left border-b">
                <th class="pb-2">Produto</th>
                <th class="pb-2">Preço</th>
                <th class="pb-2">Quantidade</th>
                <th class="pb-2">Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($cart as $id => $item)
            @php
            $price = $item['price'];
            $qty = $item['quantity'];
            $subtotal = $price * $qty;
            $total += $subtotal;
            @endphp
            <tr class="border-b">
                <td class="py-3 flex items-center gap-4">
                    <img src="{{ asset('storage/products/' . ($item['photo'] ?? 'product_no_image.png')) }}"
                        alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                    <span>{{ $item['name'] }}</span>
                </td>
                <td>{{ number_format($price, 2, ',', '.') }} €</td>
                <td>
                    <form action="{{ route('cart.add', $id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="number" name="quantity" value="{{ $qty }}" min="1"
                            class="w-16 border rounded px-2 py-1 text-gray-800">
                        <button class="btn-rustic px-3 py-1 text-white rounded">Atualizar</button>
                    </form>
                </td>
                <td>{{ number_format($subtotal, 2, ',', '.') }} €</td>
                <td>
                    <form method="POST" action="{{ route('cart.remove', $id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">Remover</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
    $shipping = $total <= 50 ? 10 : ($total <=100 ? 5 : 0);
        $totalWithShipping=$total + $shipping;
        @endphp

        <div class="bg-gray-50 p-4 rounded mb-6">
        <p class="mb-2">Subtotal: <strong>{{ number_format($total, 2, ',', '.') }} €</strong></p>
        <p class="mb-2">Portes: <strong>{{ number_format($shipping, 2, ',', '.') }} €</strong></p>
        <p class="text-xl font-bold">Total: {{ number_format($totalWithShipping, 2, ',', '.') }} €</p>
</div>

@if(auth()->check())
{{-- Botão para mostrar o formulário de checkout --}}
<div id="checkout-button" class="text-right">
    <a href="{{ route('checkout') }}"
        class="bg-gradient-to-r from-yellow-800 to-yellow-600 hover:from-yellow-700 hover:to-yellow-500 text-white font-semibold px-6 py-3 rounded shadow-md transition duration-200 ease-in-out mb-4 inline-block">
        🛒 Proceder para Checkout
    </a>

</div>
@else
<p class="text-red-600 mb-4">Precisas de te autenticar para finalizar a compra.</p>
<a href="{{ route('login') }}" class="btn-rustic px-6 py-3 text-white rounded inline-block">Fazer Login</a>
@endif

<form method="POST" action="{{ route('cart.clear') }}" class="mt-6">
    @csrf
    <button type="submit" class="text-sm text-red-600 hover:underline">Esvaziar Carrinho</button>
</form>
@endif
</div>
@endsection