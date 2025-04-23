@extends('layouts.app')

@section('title', 'Gestão de Utilizadores')

@section('content')
<div class="max-w-7xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-3xl font-bold mb-6">Gestão de Utilizadores</h1>

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Foto</th>
                <th class="border px-4 py-2">Nome</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Tipo</th>
                <th class="border px-4 py-2">Bloqueado?</th>
                <th class="border px-4 py-2">Ações</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $u)
            <tr class="{{ $u->trashed() ? 'bg-red-50' : '' }}">
                <td class="border px-4 py-2 text-center">
                    <img src="{{ asset('storage/users/' . ($u->photo ?? 'anonymous.png')) }}"
                         alt="Foto de {{ $u->name }}"
                         class="w-12 h-12 rounded-full object-cover mx-auto">
                </td>
                <td class="border px-4 py-2">{{ $u->name }}</td>
                <td class="border px-4 py-2">{{ $u->email }}</td>
                <td class="border px-4 py-2">{{ ucfirst($u->type) }}</td>
                <td class="border px-4 py-2 text-center">
                    {!! $u->blocked ? '✔️' : '—' !!}
                </td>
                <td class="border px-4 py-2 space-x-2">
                    <a href="{{ route('settings.users.edit', $u) }}"
                       class="text-blue-600 hover:underline">Editar</a>

                    @if($u->blocked)
                        <form method="POST"
                              action="{{ route('settings.users.unblock', $u) }}"
                              class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:underline">
                                Desbloquear
                            </button>
                        </form>
                    @else
                        <form method="POST"
                              action="{{ route('settings.users.block', $u) }}"
                              class="inline">
                            @csrf
                            <button type="submit" class="text-yellow-600 hover:underline">
                                Bloquear
                            </button>
                        </form>
                    @endif

                    @if($u->trashed())
                        <form method="POST"
                              action="{{ route('settings.users.restore', $u) }}"
                              class="inline">
                            @csrf
                            <button type="submit" class="text-purple-600 hover:underline">
                                Restaurar
                            </button>
                        </form>
                    @else
                        <form method="POST"
                              action="{{ route('settings.users.destroy', $u) }}"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">
                                Remover
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
