@extends('layouts.app')

@section('title','Produtos')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Produtos</h1>
    <a href="{{ route('settings.products.create') }}"
       class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded">
      + Novo Produto
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
        <th class="px-4 py-2">Imagem</th>
        <th class="px-4 py-2">Nome</th>
        <th class="px-4 py-2">Categoria</th>
        <th class="px-4 py-2">Preço</th>
        <th class="px-4 py-2">Stock</th>
        <th class="px-4 py-2">Ações</th>
      </tr>
    </thead>
    <tbody>
      @forelse($products as $prod)
      <tr class="border-t">
        <td class="px-4 py-2">
          @if($prod->photo)
            <img src="{{ asset('storage/products/'.$prod->photo) }}" class="w-16 h-16 object-cover rounded">
          @else
            —
          @endif
        </td>
        <td class="px-4 py-2">{{ $prod->name }}</td>
        <td class="px-4 py-2">{{ $prod->category->name }}</td>
        <td class="px-4 py-2">{{ number_format($prod->price,2,',','.') }} €</td>
        <td class="px-4 py-2">{{ $prod->stock }}</td>
        <td class="px-4 py-2 space-x-2">
          <a href="{{ route('settings.products.edit',$prod) }}" class="text-blue-600 hover:underline">Editar</a>
          <form action="{{ route('settings.products.destroy',$prod) }}" method="POST" class="inline">
            @csrf @method('DELETE')
            <button onclick="return confirm('Eliminar este produto?')"
                    class="text-red-600 hover:underline">
              Eliminar
            </button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="text-center py-4 text-gray-600">Nenhum produto.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
