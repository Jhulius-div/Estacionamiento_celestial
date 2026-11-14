@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('contenido')

    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0 font-weight-bold">Bienvenido, {{ auth()->user()->nombre }}</h3>
            <p>{{ now()->format('d/m/Y') }} - Resumen del estacionamiento</p>
        </div>
    </div>

    {{-- Tarjetas --}}
    <div class="row">
        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-muted mb-1">Espacios libres</p>
                    <h2 class="font-weight-bold text-success mb-0">{{ $libres }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-muted mb-1">Espacios ocupados</p>
                    <h2 class="font-weight-bold text-danger mb-0">{{ $ocupados }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-muted mb-1">Entradas de hoy</p>
                    <h2 class="font-weight-bold text-primary mb-0">{{ $entradasHoy }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-muted mb-1">Ingresos de hoy</p>
                    <h2 class="font-weight-bold text-warning mb-0">Bs {{ number_format($ingresosHoy, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Gráfico de ingresos --}}
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Ingresos de los últimos 7 días (Bs)</h4>
                    <canvas id="graficoIngresos" height="180"></canvas>
                </div>
            </div>
        </div>

        {{-- Últimos boletos --}}
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Últimos boletos</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Placa</th>
                                    <th>Espacio</th>
                                    <th>Entrada</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ultimos as $boleto)
                                    <tr>
                                        <td>{{ $boleto->id }}</td>
                                        <td>{{ $boleto->vehiculo->placa }}</td>
                                        <td>{{ $boleto->espacio->codigo }}</td>
                                        <td>{{ $boleto->hora_entrada->format('d/m H:i') }}</td>
                                        <td>
                                            @if ($boleto->hora_salida)
                                                <span class="badge badge-success">Pagado</span>
                                            @else
                                                <span class="badge badge-warning">En curso</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">Aún no hay boletos</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('celestial/vendors/chart.js/Chart.min.js') }}"></script>
    <script>
        // Gráfico de barras con los ingresos por día
        new Chart(document.getElementById('graficoIngresos'), {
            type: 'bar',
            data: {
                labels: @json($dias),
                datasets: [{
                    label: 'Ingresos (Bs)',
                    data: @json($montos),
                    backgroundColor: '#4d83ff'
                }]
            },
            options: {
                legend: { display: false },
                scales: { yAxes: [{ ticks: { beginAtZero: true } }] }
            }
        });
    </script>
@endsection
