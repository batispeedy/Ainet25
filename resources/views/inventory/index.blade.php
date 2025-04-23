@extends('layouts.app')
@section('title','Inventário')
@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-3xl font-bold mb-6">Inventário Geral</h1>
  <table class="w-full mb-4">
    <thead><tr class="border-b">
      <th>Produto</th><th>Stock</th><th>Limite Mín</th><th>Limite Máx</th><th>Ações</th>
    </tr></thead>
    <tbody>
    @foreach($products as $p)
      <tr class="border-b">
        <td>{{ $p->name }}</td>
        <td>{{ $p->stock }}</td>
        <td>{{ $p->stock_lower_limit }}</td>
        <td>{{ $p->stock_upper_limit }}</td>
        <td class="flex gap-2">
          <form method="POST" action="{{ route('inventory.adjust',$p->id) }}">
            @csrf
            <input type="number" name="quantity" value="{{ $p->stock }}" class="w-20 border rounded px-1">
            <button type="submit" class="text-blue-600 hover:underline">Ajustar</button>
          </form>
          <form method="POST" action="{{ route('inventory.supply.create') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $p->id }}">
            <input type="number" name="quantity" placeholder="Qtd" class="w-16 border rounded px-1">
            <button type="submit" class="text-green-600 hover:underline">Supply</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  {{ $products->links() }}
</div>
@endsection
