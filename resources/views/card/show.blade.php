@extends('layouts.app')

@section('title', 'Meu Cartão')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-2xl font-bold mb-4">Detalhes do Cartão</h1>
  <p><strong>Número do Cartão:</strong> {{ $card->card_number }}</p>
  <p><strong>Saldo Atual:</strong> {{ number_format($card->balance, 2, ',', '.') }} €</p>
  <div class="mt-4">
    <a href="{{ route('card.history') }}"
       class="text-blue-600 hover:underline">
      Ver Histórico de Operações
    </a>
  </div>
</div>
@endsection
