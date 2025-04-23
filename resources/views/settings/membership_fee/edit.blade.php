@extends('layouts.app')

@section('title', 'Portes & Quota')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Configurar Quota de Associado</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.membership_fee.update') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-medium mb-1">Quota de Associado (€)</label>
            <input
                type="number"
                name="membership_fee"
                step="0.01"
                value="{{ old('membership_fee', $setting->membership_fee) }}"
                class="w-full border rounded px-3 py-2 @error('membership_fee') border-red-500 @enderror"
            >
            @error('membership_fee')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="btn-rustic bg-yellow-800 hover:bg-yellow-700 text-white px-6 py-2 rounded shadow">
            Guardar
        </button>
    </form>
</div>
@endsection
