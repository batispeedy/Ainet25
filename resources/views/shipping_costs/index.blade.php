@extends('layouts.app')

@section('title','Portes de Envio')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Faixas de Portes</h1>
    <a href="{{ route('settings.shipping_costs.create') }}"
       class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded">
      + Nova Faixa
    </a>
  </div>

  @if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
      {{ session('success') }}
    </div>
  @endif

  <table class="w-full table-auto">
    <thead>
      <tr class="bg-gray-100">
        <th class="px-4 py-2">Valor Mínimo (€)</th>
        <th class="px-4 py-2">Valor Máximo (€)</th>
        <th class="px-4 py-2">Portes (€)</th>
        <th class="px-4 py-2">Ações</th>
      </tr>
    </thead>
    <tbody>
      @forelse($costs as $c)
      <tr class="border-t">
        <td class="px-4 py-2">{{ number_format($c->min_value_threshold,2,',','.') }}</td>
        <td class="px-4 py-2">{{ number_format($c->max_value_threshold,2,',','.') }}</td>
        <td class="px-4 py-2">{{ number_format($c->shipping_cost,2,',','.') }}</td>
        <td class="px-4 py-2 space-x-2">
          <a href="{{ route('settings.shipping_costs.edit',$c) }}" class="text-blue-600 hover:underline">Editar</a>
          <form action="{{ route('settings.shipping_costs.destroy',$c) }}" method="POST" class="inline">
            @csrf @method('DELETE')
            <button onclick="return confirm('Remover esta faixa?')"
                    class="text-red-600 hover:underline">Eliminar</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="4" class="text-center py-4 text-gray-600">Sem faixas definidas.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="mt-4">{{ $costs->links() }}</div>
</div>
@endsection
