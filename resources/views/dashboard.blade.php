@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Bem-vindo, {{ auth()->user()->name }}</h1>

    {{-- Cartão Virtual --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Cartão Virtual</h2>

        @php
            $card = \App\Models\Card::find(auth()->id());
        @endphp

        @if($card)
            <div class="p-4 border rounded bg-gray-50 mb-4">
                <p><strong>Número:</strong> {{ $card->card_number }}</p>
                <p><strong>Saldo:</strong> {{ number_format($card->balance, 2, ',', '.') }} €</p>
            </div>
        @else
            <p class="text-red-500">Este utilizador ainda não tem cartão associado.</p>
        @endif
    </div>

    {{-- Histórico de Encomendas --}}
    <div id="orders" class="mb-6">
        <h2 class="text-xl font-semibold mb-2">As Minhas Encomendas</h2>
        @php
            $orders = \App\Models\Order::where('member_id', auth()->id())
                        ->orderBy('date','desc')
                        ->get();
        @endphp

        @if($orders->isEmpty())
            <p class="text-gray-500">Sem encomendas registadas.</p>
        @else
            <ul class="space-y-3">
                @foreach($orders as $order)
                <li class="p-4 border rounded bg-gray-50">
                    <p>
                        <strong>Encomenda #{{ $order->id }}</strong>
                        – {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}
                    </p>
                    <p>Total: {{ number_format($order->total, 2, ',', '.') }} €
                        ({{ ucfirst($order->status) }})
                    </p>

                    @if($order->pdf_receipt)
                    <a href="{{ asset('storage/receipts/'.$order->pdf_receipt) }}"
                       target="_blank"
                       class="text-blue-600 underline">
                        Ver Recibo
                    </a>
                    @endif

                    @if($order->status === 'pending')
                    <form method="POST"
                          action="{{ route('orders.cancel', $order) }}"
                          class="mt-2"
                          onsubmit="return confirm('Cancelar esta encomenda?');">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">
                            Cancelar Encomenda
                        </button>
                    </form>
                    @endif
                </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="flex justify-between items-center">
        <a href="{{ route('store.index') }}"
           class="btn-rustic px-6 py-3 text-white rounded">
           Voltar à Loja
        </a>

        <form id="logout-form"
              action="{{ route('logout') }}"
              method="POST"
              class="inline">
            @csrf
            <button type="submit"
                    class="text-red-600 hover:underline">
                Terminar Sessão
            </button>
        </form>
    </div>
</div>
@endsection
