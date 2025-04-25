@extends('layouts.app')

@section('title', 'Carrinho de Compras')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-3xl font-bold mb-6">Carrinho</h1>

    @if(empty($cart))
        <p class="text-gray-600">O carrinho está vazio.</p>
    @else
        <table class="w-full mb-6">
            <thead>
                <tr class="text-left border-b">
                    <th class="pb-2">Produto</th>
                    <th class="pb-2">Preço Unitário</th>
                    <th class="pb-2">Quantidade</th>
                    <th class="pb-2">Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $id => $item)
                    <tr class="border-b">
                        <td class="py-3 flex items-center gap-4">
                            <img src="{{ asset('storage/products/' . ($item['photo'] ?? 'product_no_image.png')) }}"
                                 alt="{{ $item['name'] }}"
                                 class="w-16 h-16 object-cover rounded">
                            <span>{{ $item['name'] }}</span>
                        </td>
                        <td>{{ number_format($item['unit_price'], 2, ',', '.') }} €</td>
                        <td>
                            <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="number"
                                       name="quantity"
                                       value="{{ $item['quantity'] }}"
                                       min="1"
                                       class="w-16 border rounded px-2 py-1 text-gray-800">
                                <button type="submit"
                                        class="btn-rustic px-3 py-1 text-black rounded">
                                    Atualizar
                                </button>
                            </form>
                        </td>
                        <td>{{ number_format($item['subtotal'], 2, ',', '.') }} €</td>
                        <td>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">
                                    Remover
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="bg-gray-50 p-4 rounded mb-6">
            <p class="mb-2">
                Subtotal: <strong>{{ number_format($totalItems, 2, ',', '.') }} €</strong>
            </p>
            <p class="mb-2">
                Portes: <strong>{{ number_format($shippingCost, 2, ',', '.') }} €</strong>
            </p>
            <p class="text-xl font-bold">
                Total: {{ number_format($total, 2, ',', '.') }} €
            </p>
        </div>

        @auth
            <div class="text-right mb-4">
                <a href="{{ route('checkout') }}"
                   class="bg-gradient-to-r from-yellow-800 to-yellow-600 hover:from-yellow-700 hover:to-yellow-500
                          text-white font-semibold px-6 py-3 rounded shadow-md transition duration-200 ease-in-out">
                    🛒 Proceder para Checkout
                </a>
            </div>
        @else
            <p class="text-red-600 mb-4">
                Precisas de te autenticar para finalizar a compra.
            </p>
            <a href="{{ route('login') }}"
               class="btn-rustic px-6 py-3 text-white rounded inline-block">
                Fazer Login
            </a>
        @endauth

        <form action="{{ route('cart.clear') }}" method="POST" class="mt-6">
            @csrf
            <button type="submit" class="text-sm text-red-600 hover:underline">
                Esvaziar Carrinho
            </button>
        </form>
    @endif
</div>
@endsection
