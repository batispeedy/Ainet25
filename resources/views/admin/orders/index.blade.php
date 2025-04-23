@extends('layouts.app')

@section('title','Encomendas Pendentes')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-3xl font-bold mb-6">Encomendas Pendentes</h1>

  @if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
  @endif

  <table class="w-full table-auto">
    <thead>
      <tr class="bg-gray-100">
        <th class="px-4 py-2">ID</th>
        <th class="px-4 py-2">Cliente</th>
        <th class="px-4 py-2">Data</th>
        <th class="px-4 py-2">Total</th>
        <th class="px-4 py-2">Ações</th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $order)
        <tr class="border-t">
          <td class="px-4 py-2">{{ $order->id }}</td>
          <td class="px-4 py-2">{{ $order->member->name }}</td>
          <td class="px-4 py-2">{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</td>
          <td class="px-4 py-2">{{ number_format($order->total,2,',','.') }} €</td>
          <td class="px-4 py-2 space-x-2">
            <form action="{{ route('admin.orders.complete', $order) }}" method="POST" class="inline">
              @csrf
              <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded">
                Completar
              </button>
            </form>
            <button data-id="{{ $order->id }}" 
                    onclick="toggleCancel(this.dataset.id);" 
                    class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded">
              Cancelar
            </button>
          </td>
        </tr>
        <tr id="cancel-row-{{ $order->id }}" class="hidden bg-red-50">
          <td colspan="5" class="p-4">
            <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="flex items-center gap-2">
              @csrf
              <input type="text" name="reason" placeholder="Motivo de cancelamento" 
                     class="border p-2 flex-1" required>
              <button type="submit" class="bg-red-600 text-white px-4 py-1 rounded">
                Confirmar
              </button>
              <button type="button" data-id="{{ $order->id }}" onclick="toggleCancel(this.dataset.id);" class="text-gray-600 hover:underline">
                Fechar
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center py-4 text-gray-600">Nenhuma encomenda pending.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-4">{{ $orders->links() }}</div>
</div>

<script>
  function toggleCancel(id) {
    var row = document.getElementById('cancel-row-' + id);
    if (row) {
      row.classList.toggle('hidden');
    }
  }
</script>
@endsection