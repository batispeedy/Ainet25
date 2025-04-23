@extends('layouts.app')

@section('title', 'Meus Pedidos')
@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-3xl font-bold mb-6">Meus Pedidos</h1>

    @if($orders->isEmpty())
        <p class="text-gray-600">Ainda não tens encomendas.</p>
    @else
        <table class="w-full table-auto mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">#ID</th>
                    <th class="px-4 py-2">Data</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">Estado</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $order->id }}</td>
                    <td class="px-4 py-2">{{ $order->date }}</td>
                    <td class="px-4 py-2">{{ number_format($order->total,2,',','.') }} €</td>
                    <td class="px-4 py-2 text-capitalize">{{ $order->status }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline">Ver Detalhes</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $orders->links() }}
    @endif
</div>
@endsection


