@extends('layouts.app')

@section('title', 'Encomendas Pendentes')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-3xl font-bold mb-6">Encomendas Pendentes</h1>

    @if($orders->isEmpty())
        <p class="text-gray-600">Não há encomendas pendentes.</p>
    @else
        <table class="w-full table-auto mb-6">
            <thead>
                <tr class="border-b">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Cliente</th>
                    <th class="px-4 py-2 text-left">Data</th>
                    <th class="px-4 py-2 text-left">Total</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $order->id }}</td>
                    <td class="px-4 py-2">{{ $order->member->name }}</td>
                    <td class="px-4 py-2">{{ $order->date }}</td>
                    <td class="px-4 py-2">{{ number_format($order->total, 2, ',', '.') }} €</td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:underline">
                                Concluir
                            </button>
                        </form>
                        <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:underline">
                                Cancelar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $orders->links() }}
    @endif
</div>
@endsection
