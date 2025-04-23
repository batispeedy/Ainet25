@extends('layouts.app')

@section('title','Editar Faixa de Portes')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-2xl font-bold mb-6">Editar Faixa de Portes</h1>

  @if($errors->any())
    <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('settings.shipping_costs.update',$shipping_cost) }}" method="POST">
    @csrf @method('PATCH')
    <div class="mb-4">
      <label>Valor Mínimo (€)</label>
      <input type="number" step="0.01" name="min_value_threshold"
             value="{{ old('min_value_threshold',$shipping_cost->min_value_threshold) }}"
             class="w-full border rounded p-2" required>
    </div>
    <div class="mb-4">
      <label>Valor Máximo (€)</label>
      <input type="number" step="0.01" name="max_value_threshold"
             value="{{ old('max_value_threshold',$shipping_cost->max_value_threshold) }}"
             class="w-full border rounded p-2" required>
    </div>
    <div class="mb-4">
      <label>Portes (€)</label>
      <input type="number" step="0.01" name="shipping_cost"
             value="{{ old('shipping_cost',$shipping_cost->shipping_cost) }}"
             class="w-full border rounded p-2" required>
    </div>
    <div class="text-right">
      <a href="{{ route('settings.shipping_costs.index') }}"
         class="mr-4 text-gray-600 hover:underline">Cancelar</a>
      <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded">
        Guardar
      </button>
    </div>
  </form>
</div>
@endsection
