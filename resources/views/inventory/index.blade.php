@extends('layouts.app')
@section('title','Inventário')
@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">

  {{-- Mensagens de Sucesso / Erro --}}
  @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
      ✅ {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
      ⚠️ {{ session('error') }}
    </div>
  @endif

  <h1 class="text-3xl font-bold mb-6">Inventário Geral</h1>
  <table class="w-full mb-4">
    <thead>
      <tr class="border-b">
        <th class="text-left px-2 py-1">Produto</th>
        <th class="text-center px-2 py-1">Stock</th>
        <th class="text-center px-2 py-1">Limite Mín</th>
        <th class="text-center px-2 py-1">Limite Máx</th>
        <th class="text-center px-2 py-1">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $p)
      <tr class="border-b">
        <td class="px-2 py-1">{{ $p->name }}</td>
        <td class="text-center px-2 py-1">{{ $p->stock }}</td>
        <td class="text-center px-2 py-1">{{ $p->stock_lower_limit }}</td>
        <td class="text-center px-2 py-1">{{ $p->stock_upper_limit }}</td>
        <td class="flex gap-4 px-2 py-1">
          {{-- Ajuste Manual de Stock --}}
          <form method="POST" action="{{ route('inventory.adjust', $p->id) }}" class="flex items-center gap-1">
            @csrf
            <input type="number" name="quantity" value="{{ $p->stock }}" class="w-20 border rounded px-1 py-1" />
            <button type="submit" class="text-blue-600 hover:underline">Ajustar</button>
          </form>

          {{-- Supply Order (manual ou automática se qtd vazia) --}}
          <form method="POST" action="{{ route('inventory.supply.create') }}" class="flex items-center gap-1">
            @csrf
            <input type="hidden" name="product_id" value="{{ $p->id }}">
            <input type="number"
                   name="quantity"
                   placeholder="Qtd (opcional)"
                   class="w-24 border rounded px-1 py-1"
            >
            <button type="submit" class="text-green-600 hover:underline">Supply</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">
    {{ $products->links() }}
  </div>
</div>
@endsection
