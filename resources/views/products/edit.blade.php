@extends('layouts.app')

@section('title','Editar Produto')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-2xl font-bold mb-6">Editar Produto</h1>

  @if($errors->any())
    <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('settings.products.update',$product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PATCH')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block font-medium text-gray-700">Nome</label>
        <input type="text" name="name" value="{{ old('name',$product->name) }}"
               class="w-full border rounded p-2" required>
      </div>
      <div>
        <label class="block font-medium text-gray-700">Categoria</label>
        <select name="category_id" class="w-full border rounded p-2" required>
          <option value="">— Seleciona —</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                    {{ old('category_id',$product->category_id)==$cat->id?'selected':'' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block font-medium text-gray-700">Preço (€)</label>
        <input type="number" step="0.01" name="price" value="{{ old('price',$product->price) }}"
               class="w-full border rounded p-2" required>
      </div>
      <div>
        <label class="block font-medium text-gray-700">Stock</label>
        <input type="number" name="stock" value="{{ old('stock',$product->stock) }}"
               class="w-full border rounded p-2" required>
      </div>
      <div>
        <label class="block font-medium text-gray-700">Desconto Mín.</label>
        <input type="number" name="discount_min_qty"
               value="{{ old('discount_min_qty',$product->discount_min_qty) }}"
               class="w-full border rounded p-2">
      </div>
      <div>
        <label class="block font-medium text-gray-700">Desconto (€)</label>
        <input type="number" step="0.01" name="discount"
               value="{{ old('discount',$product->discount) }}"
               class="w-full border rounded p-2">
      </div>
      <div>
        <label class="block font-medium text-gray-700">Limite Baixo Stock</label>
        <input type="number" name="stock_lower_limit"
               value="{{ old('stock_lower_limit',$product->stock_lower_limit) }}"
               class="w-full border rounded p-2">
      </div>
      <div>
        <label class="block font-medium text-gray-700">Limite Alto Stock</label>
        <input type="number" name="stock_upper_limit"
               value="{{ old('stock_upper_limit',$product->stock_upper_limit) }}"
               class="w-full border rounded p-2">
      </div>
    </div>

    <div class="mt-4">
      <label class="block font-medium text-gray-700">Descrição</label>
      <textarea name="description" rows="4"
                class="w-full border rounded p-2">{{ old('description',$product->description) }}</textarea>
    </div>

    <div class="mt-4">
      <label class="block font-medium text-gray-700">Foto Atual</label>
      @if($product->photo)
        <img src="{{ asset('storage/products/'.$product->photo) }}"
             class="w-24 h-24 object-cover rounded mb-3">
      @else
        <p class="text-gray-500">Sem imagem.</p>
      @endif
      <label class="block font-medium text-gray-700 mt-2">Alterar Foto</label>
      <input type="file" name="photo" accept="image/*" class="block mt-1">
    </div>

    <div class="text-right mt-6">
      <a href="{{ route('settings.products.index') }}"
         class="mr-4 text-gray-600 hover:underline">Cancelar</a>
      <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded">
        Guardar Alterações
      </button>
    </div>
  </form>
</div>
@endsection
