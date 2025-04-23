{{-- resources/views/supply_orders/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Ordens de Reposição')
@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-2xl font-bold mb-4">Ordens de Reposição</h1>

  @if($orders->isEmpty())
    <p class="text-gray-600">Não há ordens de reposição registadas.</p>
  @else
    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100">
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Produto</th>
          <th class="border px-4 py-2">Quantidade</th>
          <th class="border px-4 py-2">Registada por</th>
          <th class="border px-4 py-2">Estado</th>
          <th class="border px-4 py-2">Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
        <tr class="hover:bg-gray-50">
          <td class="border px-4 py-2">{{ $order->id }}</td>
          <td class="border px-4 py-2">{{ optional($order->product)->name ?? '—' }}</td>
          <td class="border px-4 py-2 text-center">{{ $order->quantity }}</td>
          <td class="border px-4 py-2">{{ optional($order->registeredBy)->name ?? '—' }}</td>
          <td class="border px-4 py-2 capitalize">{{ $order->status }}</td>
          <td class="border px-4 py-2 space-x-2">
            @if($order->status === 'requested')
              <form method="POST" action="{{ route('supply_orders.complete', $order) }}" class="inline">
                @csrf
                <button class="text-green-600 hover:underline">Completar</button>
              </form>
            @else
              <span class="text-gray-500">—</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="mt-4">
      {{ $orders->links() }}
    </div>
  @endif
</div>
@endsection
