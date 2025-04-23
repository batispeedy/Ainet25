@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">O Meu Perfil</h1>

    @if ($errors->any())
    <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800">
        <strong>Erros no formulário:</strong>
        <ul class="mt-2 list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 flex items-center">
        ✅ <span class="ml-2">{{ session('success') }}</span>
    </div>
    @elseif(session('error'))
    <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 flex items-center">
        ❌ <span class="ml-2">{{ session('error') }}</span>
    </div>
    @endif

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
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full border rounded px-3 py-2 mt-1 focus:ring-yellow-600 focus:border-yellow-600">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">NIF</label>
                <input type="text" name="nif" value="{{ old('nif', auth()->user()->nif) }}" class="w-full border rounded px-3 py-2 mt-1 focus:ring-yellow-600 focus:border-yellow-600">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Morada</label>
                <input type="text" name="default_delivery_address" value="{{ old('default_delivery_address', auth()->user()->default_delivery_address) }}" class="w-full border rounded px-3 py-2 mt-1 focus:ring-yellow-600 focus:border-yellow-600">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto de Perfil</label>
                <img src="{{ asset('storage/users/' . (auth()->user()->photo ?? 'anonymous.png')) }}" alt="Foto" class="w-24 h-24 rounded-full mb-3">
                <input type="file" name="photo" accept="image/*" class="block w-full text-sm text-gray-700">
            </div>

            <div class="flex justify-between mt-4">
                <button type="submit" class="btn-rustic bg-yellow-800 hover:bg-yellow-700 text-white px-6 py-2 rounded shadow">Guardar Alterações</button>
                <button form="delete-profile-form" type="submit" class="text-sm text-red-600 hover:underline">Eliminar Conta</button>
            </div>
        </form>
        @auth
        @if (auth()->user()->type === 'pending_member')
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mt-6">
            <p class="text-yellow-800 mb-2">Ainda não és membro efetivo.</p>
            <a href="{{ route('membership.show') }}"
                class="inline-block bg-yellow-700 text-black font-semibold px-5 py-2 rounded shadow hover:bg-yellow-600 transition duration-200">
                💳 Tornar-me Membro
            </a>
        </div>
        @endif
        @endauth
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

            <button type="submit" class="btn-rustic bg-yellow-800 hover:bg-yellow-700 text-white px-6 py-2 rounded shadow">Alterar Palavra-passe</button>
        </form>
    </div>

    <!-- Cartão Virtual -->
    <div id="cartao" class="tab-content mb-12 hidden">
        <h2 class="text-xl font-semibold mb-3 text-gray-800">Cartão Virtual</h2>

        @if($card)
        <div class="p-4 border rounded bg-gray-50 mb-4">
            <p><strong>Número:</strong> {{ $card->card_number }}</p>
            <p><strong>Titular:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Saldo:</strong> {{ number_format($card->balance, 2, ',', '.') }} €</p>
        </div>

        <!-- Formulário de Top-up -->
        <form method="POST" action="{{ route('profile.topup') }}" class="bg-yellow-50 p-4 rounded shadow space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium">Valor a carregar (€)</label>
                <input type="number" name="value" step="0.01" min="1" required class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div>
                <label class="block text-sm font-medium">Método de Pagamento</label>
                <select name="payment_type" id="payment_type" required class="w-full border rounded px-3 py-2 mt-1">
                    <option value="">Seleciona</option>
                    <option value="Visa">Visa</option>
                    <option value="PayPal">PayPal</option>
                    <option value="MBWAY">MB WAY</option>
                </select>
            </div>

            <div id="visa_fields" class="hidden space-y-2">
                <label class="block text-sm">Nº Cartão (16 dígitos)</label>
                <input type="text" name="card_number" class="w-full border rounded px-3 py-2" placeholder="1234567812345678">

                <label class="block text-sm">CVC (3 dígitos)</label>
                <input type="text" name="cvc_code" class="w-full border rounded px-3 py-2" placeholder="123">
            </div>

            <div id="paypal_fields" class="hidden space-y-2">
                <label class="block text-sm">Email PayPal</label>
                <input type="email" name="email_address" class="w-full border rounded px-3 py-2" placeholder="teu@email.com">
            </div>

            <div id="mbway_fields" class="hidden space-y-2">
                <label class="block text-sm">Número MB WAY</label>
                <input type="text" name="phone_number" class="w-full border rounded px-3 py-2" placeholder="9XXXXXXXX">
            </div>

            <button type="submit" class="btn-rustic text-black px-6 py-2 rounded mt-4">💳 Carregar Saldo</button>
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
                <span class="font-semibold">{{ ucfirst($op->type) }}</span> de {{ number_format($op->value, 2, ',', '.') }} € com {{ $op->payment_type ?? 'virtual card' }} em {{ $op->created_at->format('d/m/Y H:i') }}
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
                    <p>
                        <strong>Encomenda #{{ $order->id }}</strong>
                        – {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}
                    </p>
                    <p>
                        Total:
                        {{ number_format($order->total, 2, ',', '.') }} €
                        ({{ ucfirst($order->status) }})
                    </p>

                    @if($order->pdf_receipt)
                        <a href="{{ asset('storage/receipts/'.$order->pdf_receipt) }}"
                           target="_blank" class="text-blue-600 underline">
                            Ver Recibo
                        </a>
                    @endif

                    @if($order->status === 'pending')
                        <form method="POST"
                              action="{{ route('orders.cancel', $order) }}"
                              class="mt-2"
                              onsubmit="return confirm('Tens a certeza que queres cancelar esta encomenda?');">
                            @csrf
                            <button type="submit"
                                    class="text-red-600 hover:underline">
                                Cancelar Encomenda
                            </button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabs = document.querySelectorAll(".tab-link");
        const contents = document.querySelectorAll(".tab-content");

        function showTab(tabId) {
            contents.forEach(c => c.classList.add("hidden"));
            document.getElementById(tabId).classList.remove("hidden");
        }

        tabs.forEach(tab => {
            tab.addEventListener("click", function(e) {
                e.preventDefault();
                showTab(this.dataset.tab);
            });
        });

        if (tabs.length) {
            tabs[0].click();
        }

        const paymentSelect = document.getElementById("payment_type");
        const visa = document.getElementById("visa_fields");
        const paypal = document.getElementById("paypal_fields");
        const mbway = document.getElementById("mbway_fields");

        function toggleFields() {
            visa.classList.add('hidden');
            paypal.classList.add('hidden');
            mbway.classList.add('hidden');

            switch (paymentSelect.value) {
                case 'Visa':
                    visa.classList.remove('hidden');
                    break;
                case 'PayPal':
                    paypal.classList.remove('hidden');
                    break;
                case 'MBWAY':
                    mbway.classList.remove('hidden');
                    break;
            }
        }

        paymentSelect.addEventListener("change", toggleFields);
    });
</script>
@endsection