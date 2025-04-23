@extends('layouts.app')
@section('title','Minhas Estatísticas')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <h1 class="text-3xl font-extrabold">As Minhas Estatísticas</h1>

    {{-- Vendas por Mês --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">As Minhas Vendas por Mês</h2>
        @if($salesByMonth->isEmpty())
            <p class="text-gray-500">Ainda não fizeste nenhuma encomenda.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Mês</th>
                            <th class="px-4 py-2 text-right">Total (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesByMonth as $month => $total)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                            <td class="px-4 py-2">{{ $month }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($total,2,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Vendas por Categoria --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Vendas por Categoria</h2>
        @if($salesByCategory->isEmpty())
            <p class="text-gray-500">Sem vendas por categoria.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Categoria</th>
                            <th class="px-4 py-2 text-right">Total (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesByCategory as $cat => $total)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                            <td class="px-4 py-2">{{ $cat }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($total,2,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
