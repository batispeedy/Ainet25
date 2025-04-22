@extends('layouts.app')

@section('title', 'Compra Finalizada')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded shadow p-6 text-center">
    <h1 class="text-3xl font-bold text-green-700 mb-6">✅ Compra Finalizada com Sucesso!</h1>

    <p class="text-lg text-gray-700 mb-4">Obrigado pela tua compra, {{ auth()->user()->name }}!</p>

    <p class="text-gray-600 mb-6">
        Receberás um email com os detalhes da tua encomenda. Podes consultar o histórico no teu <a href="{{ route('profile.edit') }}" class="text-blue-600 underline">perfil</a>.
    </p>

    <a href="{{ route('store.index') }}"
       class="bg-yellow-800 hover:bg-yellow-700 text-white font-semibold px-6 py-3 rounded shadow inline-block transition duration-200 ease-in-out">
        ← Voltar à Loja
    </a>
</div>
@endsection
