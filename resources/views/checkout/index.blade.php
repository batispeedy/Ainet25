@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Finalizar Encomenda</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-50 p-4 rounded mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Resumo da Encomenda</h2>

        @php $total = 0; @endphp
        <ul class="space-y-3">
            @foreach(session('cart', []) as $id => $item)
                @php
                    $price = $item['price'];
                    $qty = $item['quantity'];
                    $subtotal = $price * $qty;
                    $total += $subtotal;
                @endphp
                <li class="flex justify-between">
                    <div>{{ $item['name'] }} (x{{ $qty }})</div>
                    <div>{{ number_format($subtotal, 2, ',', '.') }} €</div>
                </li>
            @endforeach
        </ul>

        @php
            $shipping = $total <= 50 ? 10 : ($total <= 100 ? 5 : 0);
            $totalWithShipping = $total + $shipping;
        @endphp

        <hr class="my-4">

        <p class="text-sm">Portes: <strong>{{ number_format($shipping, 2, ',', '.') }} €</strong></p>
        <p class="text-lg font-bold">Total: {{ number_format($totalWithShipping, 2, ',', '.') }} €</p>
    </div>

    <form method="POST" action="{{ route('checkout.confirm') }}">
    @csrf

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">NIF</label>
        <input type="text" name="nif" value="{{ old('nif', auth()->user()->nif) }}"
               class="w-full border rounded px-3 py-2 mt-1" required>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Morada de Entrega</label>
        <input type="text" name="address" value="{{ old('address', auth()->user()->default_delivery_address) }}"
               class="w-full border rounded px-3 py-2 mt-1" required>
    </div>

    <!-- NOVO: Método de Pagamento -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">Método de Pagamento</label>
        <select name="payment_type" class="w-full border rounded px-3 py-2 mt-1" required>
            <option value="">Seleciona</option>
            <option value="Visa">Visa</option>
            <option value="PayPal">PayPal</option>
            <option value="MBWAY">MB WAY</option>
        </select>
    </div>

    <div class="text-right">
        <button type="submit"
                class="bg-gradient-to-r from-yellow-800 to-yellow-600 hover:from-yellow-700 hover:to-yellow-500 text-white font-semibold px-6 py-3 rounded shadow-md transition duration-200 ease-in-out">
            ✅ Confirmar Compra
        </button>
    </div>
</form>

    <div class="mt-6 text-left">
        <a href="{{ route('cart.index') }}" class="text-blue-600 hover:underline">← Voltar ao Carrinho</a>
    </div>
</div>
@endsection
