@extends('layouts.app')

@section('title', 'Nova Categoria')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-2xl font-bold mb-6">Criar Categoria</h1>

  @if($errors->any())
    <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-4">
      <label class="block font-medium">Nome</label>
      <input type="text" name="name" value="{{ old('name') }}"
             class="w-full border rounded p-2" required>
    </div>

    <div class="mb-4">
      <label class="block font-medium">Imagem (opcional)</label>
      <input type="file" name="image" accept="image/*" class="w-full">
    </div>

    <div class="text-right">
      <a href="{{ route('categories.index') }}"
         class="mr-4 text-gray-600 hover:underline">Cancelar</a>
      <button type="submit"
              class="bg-green-600 hover:bg-green-500 text-white px-6 py-2 rounded">
        Criar
      </button>
    </div>
  </form>
</div>
@endsection
