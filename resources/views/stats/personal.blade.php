@extends('layouts.app')

@section('title', 'Minhas Estatísticas')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-3xl font-bold mb-6">As Minhas Estatísticas</h1>

  <div class="space-y-6">
    <div class="bg-gray-50 p-4 rounded shadow">
      <h2 class="text-xl font-semibold">Total Gasto</h2>
      <p class="text-2xl">{{ number_format($totalSpent,2,',','.') }} €</p>
    </div>
    <div class="bg-gray-50 p-4 rounded shadow">
      <h2 class="text-xl font-semibold">Número de Encomendas</h2>
      <p class="text-2xl">{{ $ordersCount }}</p>
    </div>
    <div class="bg-gray-50 p-4 rounded shadow">
      <h2 class="text-xl font-semibold">Média por Encomenda</h2>
      <p class="text-2xl">{{ number_format($averagePerOrder,2,',','.') }} €</p>
    </div>
  </div>
</div>
@endsection
