@extends('layouts.app')

@section('title', 'Confirmação de Adesão')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded shadow p-6 text-center">
    <h1 class="text-3xl font-bold mb-4 text-yellow-800">🎉 Bem-vindo ao Clube!</h1>

    <p class="text-lg text-gray-800 mb-6">
        A tua quota de adesão foi paga com sucesso. Já és um membro oficial da loja gourmet Grocery Club!
    </p>

    <div class="bg-green-100 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded mb-6" role="alert">
        <p class="font-bold">Sucesso!</p>
        <p>O teu saldo foi atualizado e a tua conta agora tem acesso total ao clube.</p>
    </div>

    <a href="{{ route('store.index') }}"
       class="inline-block bg-yellow-800 hover:bg-yellow-700 text-white font-semibold px-6 py-3 rounded shadow-md transition duration-200 ease-in-out">
        🍷 Ir para a Loja
    </a>
</div>
@endsection
