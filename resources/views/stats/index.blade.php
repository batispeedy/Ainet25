@extends('layouts.app')
@section('title','Dashboard de Estatísticas')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <h1 class="text-3xl font-extrabold text-gray-800">Dashboard de Estatísticas</h1>

    {{-- Gráficos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Vendas por Mês</h2>
            <div class="relative h-64">
                <canvas id="salesByMonthChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Vendas por Categoria</h2>
            <div class="relative h-64">
                <canvas id="salesByCategoryChart"></canvas>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Vendas por Produto</h2>
            <div class="relative h-64">
                <canvas id="salesByProductChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Vendas por Membro</h2>
            <div class="relative h-64">
                <canvas id="salesByMemberChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Top 10 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Top 10 Membros --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Top 10 Membros</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Membro</th>
                            <th class="px-4 py-2 text-right">Total (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesByMember->take(10) as $member => $total)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $member }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($total,2,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top 10 Artigos --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Top 10 Artigos</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Produto</th>
                            <th class="px-4 py-2 text-right">Total (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesByProduct->take(10) as $product => $total)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $product }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($total,2,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Scripts para Chart.js --}}
@section('scripts')
  {{-- Dados em JSON para o script externo --}}
  <script type="application/json" id="stats-data">
    {!! json_encode([
      'salesByMonth'    => $salesByMonth,
      'salesByCategory' => $salesByCategory,
      'salesByProduct'  => $salesByProduct,
      'salesByMember'   => $salesByMember
    ]) !!}
  </script>

  {{-- Lógica externa de gráficos --}}
  <script src="{{ asset('js/stats-dashboard.js') }}" defer></script>
@endsection
