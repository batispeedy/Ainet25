@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Bem-vindo, {{ auth()->user()->name }}</h1>

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Cartão Virtual</h2>

        @php
            $card = \App\Models\Card::find(auth()->id());
        @endphp

        @if($card)
            <div class="p-4 border rounded bg-gray-50">
                <p><strong>Número:</strong> {{ $card->card_number }}</p>
                <p><strong>Saldo:</strong> {{ number_format($card->balance, 2, ',', '.') }} €</p>
            </div>
        @else
            <p class="text-red-500">Este utilizador ainda não tem cartão associado.</p>
        @endif
    </div>

    <div class="flex justify-between items-center">
        <a href="{{ route('store.index') }}" class="btn-rustic px-6 py-3 text-white rounded">Voltar à Loja</a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-red-600 hover:underline">Terminar Sessão</button>
        </form>
    </div>
</div>
@endsection
