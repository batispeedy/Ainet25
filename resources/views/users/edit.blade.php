@extends('layouts.app')

@section('title', 'Editar Utilizador')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Editar {{ $user->name }}</h1>

    <form method="POST"
          action="{{ route('settings.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium mb-1">Nome</label>
            <input type="text" name="name"
                   value="{{ old('name', $user->name) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Email</label>
            <input type="email" name="email"
                   value="{{ old('email', $user->email) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Tipo de Utilizador</label>
            <select name="type" class="w-full border rounded px-3 py-2">
                <option value="member"  {{ $user->type=='member'  ? 'selected' : '' }}>Membro</option>
                <option value="board"   {{ $user->type=='board'   ? 'selected' : '' }}>Board</option>
                <option value="employee"{{ $user->type=='employee'? 'selected' : '' }}>Employee</option>
            </select>
        </div>

        <button type="submit"
                class="bg-yellow-800 hover:bg-yellow-700 text-white px-6 py-2 rounded shadow">
            Guardar
        </button>
        <a href="{{ route('settings.users.index') }}"
           class="ml-4 text-gray-600 hover:underline">Cancelar</a>
    </form>
</div>
@endsection
