@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard de Estatísticas</h1>

    <div class="row">
        <div class="col-md-6">
            <canvas id="salesByMonthChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="salesByCategoryChart"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="salesByProductChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="salesByUserChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Vendas por mês
    const monthLabels = {!! json_encode(array_keys($salesByMonth)) !!};
    const monthData   = {!! json_encode(array_values($salesByMonth)) !!};

    new Chart(document.getElementById('salesByMonthChart'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Vendas por Mês',
                data: monthData,
                fill: false,
                tension: 0.1
            }]
        }
    });

    // Vendas por categoria
    const categoryLabels = {!! json_encode(array_keys($salesByCategory)) !!};
    const categoryData   = {!! json_encode(array_values($salesByCategory)) !!};

    new Chart(document.getElementById('salesByCategoryChart'), {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Vendas por Categoria',
                data: categoryData
            }]
        }
    });

    // Vendas por produto
    const productLabels = {!! json_encode(array_keys($salesByProductData)) !!};
    const productData   = {!! json_encode(array_values($salesByProductData)) !!};

    new Chart(document.getElementById('salesByProductChart'), {
        type: 'bar',
        data: {
            labels: productLabels,
            datasets: [{
                label: 'Vendas por Produto',
                data: productData
            }]
        }
    });

    // Vendas por utilizador
    const userLabels = {!! json_encode(array_keys($salesByUserData)) !!};
    const userData   = {!! json_encode(array_values($salesByUserData)) !!};

    new Chart(document.getElementById('salesByUserChart'), {
        type: 'bar',
        data: {
            labels: userLabels,
            datasets: [{
                label: 'Vendas por Utilizador',
                data: userData
            }]
        }
    });
</script>
@endsection
