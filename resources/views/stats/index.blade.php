@extends('layouts.app')

@section('title', 'Painel de Estatísticas')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-3xl font-bold mb-6">Estatísticas Gerais</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="bg-gray-50 p-4 rounded shadow">
      <h2 class="text-xl font-semibold mb-2">Vendas Mensais (€)</h2>
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left">Mês</th>
            <th class="text-right">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($salesByMonth as $month => $total)
          <tr>
            <td>{{ \Carbon\Carbon::parse($month.'-01')->format('M/Y') }}</td>
            <td class="text-right">{{ number_format($total,2,',','.') }} €</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="bg-gray-50 p-4 rounded shadow">
      <h2 class="text-xl font-semibold mb-2">Total de Membros</h2>
      <p class="text-4xl font-bold">{{ $newMembersCount }}</p>
    </div>

    <div class="bg-gray-50 p-4 rounded shadow md:col-span-2">
      <h2 class="text-xl font-semibold mb-2">Encomendas por Estado</h2>
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left">Status</th>
            <th class="text-right">Contagem</th>
          </tr>
        </thead>
        <tbody>
          @foreach($ordersByStatus as $status => $count)
          <tr>
            <td>{{ ucfirst($status) }}</td>
            <td class="text-right">{{ $count }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
