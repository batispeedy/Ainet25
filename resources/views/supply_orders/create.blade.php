@extends('layouts.app')

@section('title', 'Nova Ordem de Reposição')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-3xl font-bold mb-6">Nova Ordem de Reposição</h1>

    <form action="{{ route('supply_orders.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="product_id" class="block text-sm font-medium text-gray-700">Produto</label>
            <select name="product_id" id="product_id" required
                    class="w-full border rounded px-3 py-2 mt-1">
                <option value="">-- Seleciona um produto --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantidade</label>
            <input type="number" name="quantity" min="1" required
                   class="w-full border rounded px-3 py-2 mt-1">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('supply_orders.index') }}" class="mr-4 text-sm text-gray-600 hover:underline">Cancelar</a>
            <button class="btn-rustic px-6 py-2 text-white rounded">Criar Ordem</button>
        </div>
    </form>
</div>
@endsection
