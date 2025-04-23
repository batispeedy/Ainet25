@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Minhas Estatísticas</h1>

    <div class="row">
        <div class="col-md-6">
            <canvas id="personalSalesByMonthChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="personalSalesByCategoryChart"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="personalSalesByProductChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Minhas vendas por mês
    const monthLabels = {!! json_encode(array_keys($salesByMonth)) !!};
    const monthData   = {!! json_encode(array_values($salesByMonth)) !!};

    new Chart(document.getElementById('personalSalesByMonthChart'), {
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

    // Minhas vendas por categoria
    const categoryLabels = {!! json_encode(array_keys($salesByCategory)) !!};
    const categoryData   = {!! json_encode(array_values($salesByCategory)) !!};

    new Chart(document.getElementById('personalSalesByCategoryChart'), {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Vendas por Categoria',
                data: categoryData
            }]
        }
    });

    // Minhas vendas por produto
    const productLabels = {!! json_encode(array_keys($salesByProductData)) !!};
    const productData   = {!! json_encode(array_values($salesByProductData)) !!};

    new Chart(document.getElementById('personalSalesByProductChart'), {
        type: 'bar',
        data: {
            labels: productLabels,
            datasets: [{
                label: 'Vendas por Produto',
                data: productData
            }]
        }
    });
</script>
@endsection
