@extends('layouts.app')
@section('title','Dashboard de Estatísticas')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <h1 class="text-3xl font-extrabold text-gray-800">Estatísticas Gerais</h1>

    {{-- Grid: Vendas por Mês & Vendas por Categoria --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Vendas por Mês --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Vendas por Mês</h2>
            @if($salesByMonth->isEmpty())
                <p class="text-gray-500">Sem vendas registadas.</p>
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
                                <td class="px-4 py-2 text-right">{{ number_format($total, 2, ',', '.') }}</td>
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
                            @foreach($salesByCategory as $category => $total)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                                <td class="px-4 py-2">{{ $category }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($total, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Grid: Vendas por Produto & Vendas por Membro --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Vendas por Produto --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Vendas por Produto</h2>
            @if($salesByProduct->isEmpty())
                <p class="text-gray-500">Sem vendas por produto.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Produto</th>
                                <th class="px-4 py-2 text-right">Total (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesByProduct as $product => $total)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                                <td class="px-4 py-2">{{ $product }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($total, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Vendas por Membro --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Vendas por Membro</h2>
            @if($salesByMember->isEmpty())
                <p class="text-gray-500">Sem vendas por membro.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Membro</th>
                                <th class="px-4 py-2 text-right">Total (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesByMember as $member => $total)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                                <td class="px-4 py-2">{{ $member }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($total, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
