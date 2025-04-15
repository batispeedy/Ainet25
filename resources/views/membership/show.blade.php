@extends('layouts.app')

@section('title', 'Pagar Quota de Adesão')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Pagar Quota de Adesão</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">✅ {{ session('success') }}</div>
    @elseif(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">❌ {{ session('error') }}</div>
    @endif

    <div class="bg-gray-50 p-4 rounded mb-6">
        <p class="text-lg text-gray-800">O valor da quota de adesão é:</p>
        <p class="text-2xl font-bold text-yellow-700 mt-2">{{ number_format($fee, 2, ',', '.') }} €</p>
    </div>

    <form method="POST" action="{{ route('membership.pay') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium">Método de Pagamento</label>
            <select name="payment_type" id="payment_type" required
                    class="w-full border rounded px-3 py-2 mt-1">
                <option value="">Seleciona</option>
                <option value="Visa">Visa</option>
                <option value="PayPal">PayPal</option>
                <option value="MBWAY">MB WAY</option>
            </select>
        </div>

        {{-- Campos Visa --}}
        <div id="visa_fields" class="hidden space-y-2">
            <label class="block text-sm">Nº Cartão (16 dígitos)</label>
            <input type="text" name="card_number" class="w-full border rounded px-3 py-2" placeholder="1234567812345678">

            <label class="block text-sm">CVC (3 dígitos)</label>
            <input type="text" name="cvc_code" class="w-full border rounded px-3 py-2" placeholder="123">
        </div>

        {{-- Campos PayPal --}}
        <div id="paypal_fields" class="hidden space-y-2">
            <label class="block text-sm">Email PayPal</label>
            <input type="email" name="email_address" class="w-full border rounded px-3 py-2" placeholder="teu@email.com">
        </div>

        {{-- Campos MB WAY --}}
        <div id="mbway_fields" class="hidden space-y-2">
            <label class="block text-sm">Número MB WAY</label>
            <input type="text" name="phone_number" class="w-full border rounded px-3 py-2" placeholder="9XXXXXXXX">
        </div>

        <button type="submit"
                class="bg-gradient-to-r from-yellow-800 to-yellow-600 hover:from-yellow-700 hover:to-yellow-500 text-white font-semibold px-6 py-3 rounded shadow-md transition duration-200 ease-in-out">
            💳 Pagar Quota
        </button>
    </form>

    <div class="mt-6 text-left">
        <a href="{{ route('store.index') }}" class="text-blue-600 hover:underline">← Voltar à Loja</a>
    </div>
</div>

{{-- Script para mostrar campos dinâmicos --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const paymentSelect = document.getElementById("payment_type");
        const visaFields = document.getElementById("visa_fields");
        const paypalFields = document.getElementById("paypal_fields");
        const mbwayFields = document.getElementById("mbway_fields");

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
    });
</script>
@endsection
