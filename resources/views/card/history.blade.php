@extends('layouts.app')

@section('title', 'Histórico de Operações')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-2xl font-bold mb-4">Histórico de Operações</h1>
  @if($operations->isEmpty())
    <p class="text-gray-600">Não há operações registadas.</p>
  @else
    <table class="w-full table-auto mb-4">
      <thead>
        <tr class="border-b">
          <th class="px-4 py-2 text-left">Data</th>
          <th class="px-4 py-2 text-left">Tipo</th>
          <th class="px-4 py-2 text-right">Valor (€)</th>
          <th class="px-4 py-2 text-left">Detalhes</th>
        </tr>
      </thead>
      <tbody>
        @foreach($operations as $op)
        <tr class="border-b">
          <td class="px-4 py-2">{{ $op->date }}</td>
          <td class="px-4 py-2 capitalize">{{ $op->type }}</td>
          <td class="px-4 py-2 text-right">{{ number_format($op->value, 2, ',', '.') }}</td>
          <td class="px-4 py-2">
            {{ $op->type === 'debit' ? ucfirst($op->debit_type) : ucfirst($op->credit_type) }}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $operations->links() }}
  @endif
</div>
@endsection
