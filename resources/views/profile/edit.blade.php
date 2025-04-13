@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">O Meu Perfil</h1>

    <!-- Tabs -->
    <div class="mb-6">
        <ul class="flex border-b" id="tabs">
            <li class="mr-1"><a href="#" data-tab="dados" class="tab-link inline-block py-2 px-4 font-semibold text-gray-700 hover:text-black border-l border-t border-r rounded-t">Dados Pessoais</a></li>
            <li class="mr-1"><a href="#" data-tab="senha" class="tab-link inline-block py-2 px-4 font-semibold text-gray-700 hover:text-black border-l border-t border-r rounded-t">Alterar Palavra-passe</a></li>
            <li class="mr-1"><a href="#" data-tab="cartao" class="tab-link inline-block py-2 px-4 font-semibold text-gray-700 hover:text-black border-l border-t border-r rounded-t">Cartão Virtual</a></li>
            <li class="mr-1"><a href="#" data-tab="transacoes" class="tab-link inline-block py-2 px-4 font-semibold text-gray-700 hover:text-black border-l border-t border-r rounded-t">Transações</a></li>
            <li class="mr-1"><a href="#" data-tab="encomendas" class="tab-link inline-block py-2 px-4 font-semibold text-gray-700 hover:text-black border-l border-t border-r rounded-t">Encomendas</a></li>
        </ul>
    </div>

    <!-- Dados Pessoais -->
    <div id="dados" class="tab-content mb-12 hidden">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">NIF</label>
                <input type="text" name="nif" value="{{ old('nif', auth()->user()->nif) }}" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Morada</label>
                <input type="text" name="default_delivery_address" value="{{ old('default_delivery_address', auth()->user()->default_delivery_address) }}" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto de Perfil</label>
                <img src="{{ asset('storage/users/' . (auth()->user()->photo ?? 'anonymous.png')) }}" alt="Foto" class="w-24 h-24 rounded-full mb-3">
                <input type="file" name="photo" accept="image/*" class="block w-full text-sm text-gray-700">
            </div>

            <div class="flex justify-between mt-4">
                <button type="submit" class="btn-rustic text-white px-6 py-2 rounded">Guardar Alterações</button>

                <button form="delete-profile-form" type="submit" class="text-sm text-red-600 hover:underline">Eliminar Conta</button>
            </div>
        </form>

        <form id="delete-profile-form" method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Tens a certeza que queres eliminar a tua conta?');">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <!-- Alterar Palavra-passe -->
    <div id="senha" class="tab-content mb-12 hidden">
        <form method="POST" action="{{ route('profile.updatePassword') }}">
            @csrf

            <h2 class="text-xl font-semibold mb-4 text-gray-800">Alterar Palavra-passe</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium">Nova Palavra-passe</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Confirmar Palavra-passe</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>

            <button type="submit" class="btn-rustic text-white px-6 py-2 rounded">Alterar Palavra-passe</button>
        </form>
    </div>

    <!-- Cartão Virtual -->
    <div id="cartao" class="tab-content mb-12 hidden">
        <h2 class="text-xl font-semibold mb-3 text-gray-800">Cartão Virtual</h2>
        @if(auth()->user()->card)
            <div class="p-4 border rounded bg-gray-50 mb-4">
                <p><strong>Número:</strong> {{ auth()->user()->card->card_number }}</p>
                <p><strong>Saldo:</strong> {{ number_format(auth()->user()->card->balance, 2, ',', '.') }} €</p>
            </div>

            <form method="POST" action="{{ route('profile.topup') }}" class="bg-yellow-50 p-4 rounded shadow">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium">Valor a carregar</label>
                    <input type="number" name="value" step="0.01" min="1" class="w-full border rounded px-3 py-2 mt-1" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Método de pagamento</label>
                    <select name="payment_type" class="w-full border rounded px-3 py-2 mt-1" required>
                        <option value="">Seleciona</option>
                        <option value="Visa">Visa</option>
                        <option value="PayPal">PayPal</option>
                        <option value="MBWAY">MB WAY</option>
                    </select>
                </div>
                <button type="submit" class="btn-rustic text-white px-6 py-2 rounded">Carregar Saldo</button>
            </form>
        @else
            <p class="text-red-500">Ainda não tens cartão virtual associado.</p>
        @endif
    </div>

    <!-- Transações -->
    <div id="transacoes" class="tab-content mb-12 hidden">
        <h2 class="text-xl font-semibold mb-3 text-gray-800">Histórico de Transações</h2>
        @if($transactions->isEmpty())
            <p class="text-gray-500">Sem transações registadas.</p>
        @else
            <ul class="space-y-2">
                @foreach($transactions as $op)
                    <li class="border rounded p-3 bg-gray-50">
                        <span class="font-semibold">{{ ucfirst($op->type) }}</span> de {{ number_format($op->value, 2, ',', '.') }} € com {{ $op->payment_type }} em {{ $op->created_at->format('d/m/Y H:i') }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Encomendas -->
    <div id="encomendas" class="tab-content mb-12 hidden">
        <h2 class="text-xl font-semibold mb-3 text-gray-800">Histórico de Encomendas</h2>
        @if($orders->isEmpty())
            <p class="text-gray-500">Sem encomendas registadas.</p>
        @else
            <ul class="space-y-2">
                @foreach($orders as $order)
                    <li class="border rounded p-3 bg-gray-50">
                        <p><strong>Encomenda #{{ $order->id }}</strong> - {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        <p>Total: {{ number_format($order->total_price, 2, ',', '.') }} € ({{ ucfirst($order->status) }})</p>
                        @if($order->receipt_path)
                            <a href="{{ asset('storage/' . $order->receipt_path) }}" target="_blank" class="text-blue-600 underline">Ver Recibo</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="text-right mt-6">
        <a href="{{ route('store.index') }}" class="btn-rustic px-6 py-2 text-white rounded">Voltar à Loja</a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabs = document.querySelectorAll(".tab-link");
        const contents = document.querySelectorAll(".tab-content");

        function showTab(tabId) {
            contents.forEach(c => c.classList.add("hidden"));
            document.getElementById(tabId).classList.remove("hidden");
        }

        tabs.forEach(tab => {
            tab.addEventListener("click", function (e) {
                e.preventDefault();
                showTab(this.dataset.tab);
            });
        });

        // Mostrar a primeira tab por padrão
        if (tabs.length) {
            tabs[0].click();
        }
    });
</script>
@endsection
