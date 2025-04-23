@extends('layouts.app')

@section('title', 'Finalizar Encomenda')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Finalizar Encomenda</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Resumo da encomenda --}}
    <div class="bg-gray-50 p-4 rounded mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Resumo da Encomenda</h2>

        <table class="w-full mb-4">
            <thead>
                <tr class="border-b">
                    <th class="py-2 text-left">Produto</th>
                    <th class="py-2 text-center">Qtd</th>
                    <th class="py-2 text-right">Preço Uni.</th>
                    <th class="py-2 text-right">Desconto</th>
                    <th class="py-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                <tr class="border-b">
                    <td class="py-2">{{ $item['name'] }}</td>
                    <td class="py-2 text-center">{{ $item['quantity'] }}</td>
                    <td class="py-2 text-right">{{ number_format($item['unit_price'], 2, ',', '.') }} €</td>
                    <td class="py-2 text-right">{{ number_format($item['discount'], 2, ',', '.') }} €</td>
                    <td class="py-2 text-right">{{ number_format($item['subtotal'], 2, ',', '.') }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="border-t pt-4">
            <p class="text-sm">Subtotal: <strong>{{ number_format($totalItems, 2, ',', '.') }} €</strong></p>
            <p class="text-sm">Portes: <strong>{{ number_format($shippingCost, 2, ',', '.') }} €</strong></p>
            <p class="text-lg font-bold">Total: {{ number_format($total, 2, ',', '.') }} €</p>
        </div>
    </div>

    {{-- Formulário de checkout --}}
    <form method="POST" action="{{ route('checkout.confirm') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">NIF</label>
                <input type="text"
                       name="nif"
                       value="{{ old('nif', auth()->user()->nif) }}"
                       class="w-full border rounded px-3 py-2 mt-1"
                       required>
                @error('nif')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Morada de Entrega</label>
                <input type="text"
                       name="address"
                       value="{{ old('address', auth()->user()->default_delivery_address) }}"
                       class="w-full border rounded px-3 py-2 mt-1"
                       required>
                @error('address')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Método de Pagamento --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Método de Pagamento</label>
                <select name="payment_type"
                        id="payment_type"
                        required
                        class="w-full border rounded px-3 py-2 mt-1">
                    <option value="">Seleciona</option>
                    <option value="Visa" {{ old('payment_type')=='Visa'?'selected':'' }}>Visa</option>
                    <option value="PayPal" {{ old('payment_type')=='PayPal'?'selected':'' }}>PayPal</option>
                    <option value="MBWAY" {{ old('payment_type')=='MBWAY'?'selected':'' }}>MB WAY</option>
                </select>
                @error('payment_type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Campos Dinâmicos --}}
        <div id="visa_fields" class="hidden mt-4">
            <label class="block text-sm">Nº Cartão (16 dígitos)</label>
            <input type="text"
                   name="card_number"
                   value="{{ old('card_number') }}"
                   class="w-full border rounded px-3 py-2 mt-1"
                   placeholder="1234567812345678">
            @error('card_number')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror

            <label class="block text-sm mt-2">CVC (3 dígitos)</label>
            <input type="text"
                   name="cvc_code"
                   value="{{ old('cvc_code') }}"
                   class="w-full border rounded px-3 py-2 mt-1"
                   placeholder="123">
            @error('cvc_code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="paypal_fields" class="hidden mt-4">
            <label class="block text-sm">Email PayPal</label>
            <input type="email"
                   name="email_address"
                   value="{{ old('email_address') }}"
                   class="w-full border rounded px-3 py-2 mt-1"
                   placeholder="teu@email.com">
            @error('email_address')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="mbway_fields" class="hidden mt-4">
            <label class="block text-sm">Número MB WAY</label>
            <input type="text"
                   name="phone_number"
                   value="{{ old('phone_number') }}"
                   class="w-full border rounded px-3 py-2 mt-1"
                   placeholder="9XXXXXXXX">
            @error('phone_number')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="text-right mt-6">
            <button type="submit"
                    class="bg-gradient-to-r from-yellow-800 to-yellow-600 hover:from-yellow-700 hover:to-yellow-500
                           text-white font-semibold px-6 py-3 rounded shadow-md transition duration-200 ease-in-out">
                ✅ Confirmar Compra
            </button>
        </div>
    </form>

    <div class="mt-6 text-left">
        <a href="{{ route('cart.index') }}" class="text-blue-600 hover:underline">← Voltar ao Carrinho</a>
    </div>
</div>

{{-- Script para mostrar/esconder campos de pagamento --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const paymentSelect = document.getElementById("payment_type");
        const visaFields   = document.getElementById("visa_fields");
        const paypalFields = document.getElementById("paypal_fields");
        const mbwayFields  = document.getElementById("mbway_fields");

        function toggleFields() {
            visaFields.classList.add("hidden");
            paypalFields.classList.add("hidden");
            mbwayFields.classList.add("hidden");

            if (paymentSelect.value === "Visa") {
                visaFields.classList.remove("hidden");
            } else if (paymentSelect.value === "PayPal") {
                paypalFields.classList.remove("hidden");
            } else if (paymentSelect.value === "MBWAY") {
                mbwayFields.classList.remove("hidden");
            }
        }

        paymentSelect.addEventListener("change", toggleFields);
        toggleFields();
    });
</script>
@endsection
