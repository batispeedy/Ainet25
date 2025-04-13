@extends('layouts.app')

@section('title', 'Ordens de Reposição')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Ordens de Reposição</h1>
        <a href="{{ route('supply_orders.create') }}" class="btn-rustic px-4 py-2 text-white rounded">Nova Ordem</a>
    </div>

    @if ($orders->isEmpty())
        <p class="text-gray-600">Nenhuma ordem criada ainda.</p>
    @else
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2">Produto</th>
                    <th class="px-4 py-2">Quantidade</th>
                    <th class="px-4 py-2">Estado</th>
                    <th class="px-4 py-2">Registado por</th>
                    <th class="px-4 py-2">Criado em</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $order->product->name }}</td>
                    <td class="px-4 py-2">{{ $order->quantity }}</td>
                    <td class="px-4 py-2">
                        <span class="font-semibold {{ $order->status === 'completed' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $order->registeredBy->name }}</td>
                    <td class="px-4 py-2">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2">
                        @if($order->status === 'requested')
                            <form action="{{ route('supply_orders.complete', $order) }}" method="POST">
                                @csrf
                                <button class="text-green-600 hover:underline">Marcar como concluída</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
