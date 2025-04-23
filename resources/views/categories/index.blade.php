@extends('layouts.app')

@section('title', 'Categorias')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Categorias</h1>
    <a href="{{ route('categories.create') }}"
       class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded">
      + Nova Categoria
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
        <th class="px-4 py-2 text-left">Nome</th>
        <th class="px-4 py-2 text-left">Imagem</th>
        <th class="px-4 py-2">Ações</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $category)
      <tr class="border-t">
        <td class="px-4 py-2">{{ $category->name }}</td>
        <td class="px-4 py-2">
          @if($category->image)
            <img src="{{ asset('storage/categories/'.$category->image) }}"
                 alt="" class="w-12 h-12 object-cover rounded">
          @else
            —
          @endif
        </td>
        <td class="px-4 py-2 text-center space-x-2">
          <a href="{{ route('categories.edit', $category) }}"
             class="text-blue-600 hover:underline">Editar</a>
          <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
            @csrf @method('DELETE')
            <button type="submit" class="text-red-600 hover:underline"
                    onclick="return confirm('Eliminar esta categoria?')">
              Eliminar
            </button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="3" class="px-4 py-2 text-center text-gray-600">
          Não há categorias registadas.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-4">
    {{ $categories->links() }}
  </div>
</div>
@endsection
