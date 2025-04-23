@extends('layouts.app')

@section('title', 'Pedido #'.$order->id)

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-3xl font-bold mb-4">Detalhes do Pedido #{{ $order->id }}</h1>

    <p><strong>Data:</strong> {{ $order->date }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Morada:</strong> {{ $order->delivery_address }}</p>
    <p><strong>NIF:</strong> {{ $order->nif }}</p>

    <hr class="my-4">

    <h2 class="text-xl font-semibold mb-2">Itens</h2>
    <table class="w-full table-auto mb-4">
        <thead>
            <tr class="border-b">
                <th class="px-4 py-2 text-left">Produto</th>
                <th class="px-4 py-2 text-center">Qtd</th>
                <th class="px-4 py-2 text-right">Preço Uni.</th>
                <th class="px-4 py-2 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr class="border-b">
                <td class="px-4 py-2">{{ $item->product->name }}</td>
                <td class="px-4 py-2 text-center">{{ $item->quantity }}</td>
                <td class="px-4 py-2 text-right">{{ number_format($item->unit_price,2,',','.') }} €</td>
                <td class="px-4 py-2 text-right">{{ number_format($item->subtotal,2,',','.') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right">
        <p><strong>Subtotal:</strong> {{ number_format($order->total_items,2,',','.') }} €</p>
        <p><strong>Portes:</strong> {{ number_format($order->shipping_cost,2,',','.') }} €</p>
        <p class="text-xl font-bold">Total: {{ number_format($order->total,2,',','.') }} €</p>
    </div>

    <div class="mt-6">
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">← Voltar aos pedidos</a>
    </div>
</div>
@endsection
