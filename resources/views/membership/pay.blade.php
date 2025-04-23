@extends('layouts.app')

@section('title', 'Paga a Tua Quota')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded shadow p-6">
  <h1 class="text-3xl font-bold mb-6 text-yellow-800">Paga a Tua Quota de Adesão</h1>

  {{-- Mostrar erros de validação --}}
  @if($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="mb-6">
    <p class="text-lg">
      O valor da tua quota é <strong>{{ number_format($fee, 2, ',', '.') }} €</strong>.
    </p>
  </div>

  <form method="POST" action="{{ route('membership.pay') }}">
    @csrf

    <div class="mb-4">
      <label class="block font-medium">Método de Pagamento</label>
      <select name="payment_type" id="payment_type" class="w-full border rounded p-2">
        <option value="">— Seleciona —</option>
        <option value="Visa"  {{ old('payment_type')=='Visa'?'selected':'' }}>Visa</option>
        <option value="PayPal"{{ old('payment_type')=='PayPal'?'selected':'' }}>PayPal</option>
        <option value="MBWAY"{{ old('payment_type')=='MBWAY'?'selected':'' }}>MB WAY</option>
      </select>
    </div>

    {{-- Campos dinâmicos --}}
    <div id="visa_fields" class="hidden mb-4">
      <label>Nº Cartão (16 dígitos)</label>
      <input type="text" name="card_number" value="{{ old('card_number') }}"
             class="w-full border rounded p-2" placeholder="1234567812345678">
      <label class="mt-2">CVC (3 dígitos)</label>
      <input type="text" name="cvc_code" value="{{ old('cvc_code') }}"
             class="w-full border rounded p-2" placeholder="123">
    </div>

    <div id="paypal_fields" class="hidden mb-4">
      <label>Email PayPal</label>
      <input type="email" name="email_address" value="{{ old('email_address') }}"
             class="w-full border rounded p-2" placeholder="teu@email.com">
    </div>

    <div id="mbway_fields" class="hidden mb-4">
      <label>Número MB WAY</label>
      <input type="text" name="phone_number" value="{{ old('phone_number') }}"
             class="w-full border rounded p-2" placeholder="9XXXXXXXX">
    </div>

    <div class="text-center">
      <button type="submit"
              class="bg-yellow-800 hover:bg-yellow-700 text-white font-semibold px-6 py-3 rounded shadow">
        Pagar {{ number_format($fee, 2, ',', '.') }} €
      </button>
    </div>
  </form>
</div>

{{-- Script para toggle dos campos --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('payment_type');
    const visa   = document.getElementById('visa_fields');
    const paypal = document.getElementById('paypal_fields');
    const mbway  = document.getElementById('mbway_fields');

    function toggle() {
      visa.classList.add('hidden');
      paypal.classList.add('hidden');
      mbway.classList.add('hidden');
      if (sel.value === 'Visa') visa.classList.remove('hidden');
      if (sel.value === 'PayPal') paypal.classList.remove('hidden');
      if (sel.value === 'MBWAY') mbway.classList.remove('hidden');
    }

    sel.addEventListener('change', toggle);
    toggle();
  });
</script>
@endsection
