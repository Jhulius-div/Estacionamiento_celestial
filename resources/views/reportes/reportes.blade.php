@extends('layouts.app')

@section('titulo', 'Reportes')

@section('contenido')

    <div class="row">
        <div class="col-12 grid-margin">
            <h3 class="font-weight-bold">Reportes y estadísticas</h3>
            <p class="text-muted mb-0">Ingresos, vehículos frecuentes y ocupación del estacionamiento</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-3">
                            <label>Desde</label>
                            <input type="date" id="desde" class="form-control" value="{{ today()->subDays(30)->toDateString() }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Hasta</label>
                            <input type="date" id="hasta" class="form-control" value="{{ today()->toDateString() }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Agrupar por</label>
                            <select id="agrupar" class="form-control">
                                <option value="dia">Día</option>
                                <option value="mes">Mes</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <button type="button" id="btnFiltrar" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Resumen --}}
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card"><div class="card-body">
                <p class="card-title text-muted mb-1">Ingresos del periodo</p>
                <h2 class="font-weight-bold text-primary mb-0">Bs <span id="totalIngresos">0.00</span></h2>
            </div></div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card"><div class="card-body">
                <p class="card-title text-muted mb-1">Vehículos cobrados</p>
                <h2 class="font-weight-bold text-success mb-0"><span id="totalVehiculos">0</span></h2>
            </div></div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card"><div class="card-body">
                <p class="card-title text-muted mb-1">Ocupación en este momento</p>
                <h2 class="font-weight-bold text-danger mb-2"><span id="ocupados">0</span> de <span id="totalEspacios">0</span> espacios</h2>
                <div class="progress progress-md">
                    <div class="progress-bar bg-danger" id="barraOcupacion" role="progressbar" style="width: 0%"></div>
                </div>
            </div></div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="row">
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card"><div class="card-body">
                <h4 class="card-title">Ingresos (Bs)</h4>
                <canvas id="graficoIngresos" height="160"></canvas>
            </div></div>
        </div>
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card"><div class="card-body">
                <h4 class="card-title">Vehículos que ingresaron</h4>
                <canvas id="graficoEntradas" height="160"></canvas>
            </div></div>
        </div>
    </div>

    {{-- Tablas con botones de Excel y PDF --}}
    <div class="row">
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card"><div class="card-body">
                <h4 class="card-title">Ingresos por periodo</h4>
                <div class="table-responsive">
                    <table class="table table-striped" id="tablaIngresos">
                        <thead>
                            <tr><th>Periodo</th><th>Vehículos</th><th>Total</th></tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div></div>
        </div>
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card"><div class="card-body">
                <h4 class="card-title">Vehículos más frecuentes</h4>
                <div class="table-responsive">
                    <table class="table table-striped" id="tablaFrecuentes">
                        <thead>
                            <tr><th>Placa</th><th>Tipo</th><th>Veces</th></tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div></div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('celestial/vendors/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function () {
            var tablaIngresos = crearTabla('#tablaIngresos', true, 'Ingresos por periodo');
            var tablaFrecuentes = crearTabla('#tablaFrecuentes', true, 'Vehículos más frecuentes');

            // Gráficos vacíos: se llenan con los datos del servidor
            var graficoIngresos = new Chart(document.getElementById('graficoIngresos'), {
                type: 'bar',
                data: { labels: [], datasets: [{ label: 'Ingresos (Bs)', data: [], backgroundColor: '#4d83ff' }] },
                options: { legend: { display: false }, scales: { yAxes: [{ ticks: { beginAtZero: true } }] } }
            });

            var graficoEntradas = new Chart(document.getElementById('graficoEntradas'), {
                type: 'line',
                data: { labels: [], datasets: [{ label: 'Vehículos', data: [], borderColor: '#57b657', fill: false }] },
                options: { legend: { display: false }, scales: { yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }] } }
            });

            // Pide los reportes al servidor según los filtros
            function cargar() {
                $.get("{{ route('reportes.datos') }}", {
                    desde: $('#desde').val(),
                    hasta: $('#hasta').val(),
                    agrupar: $('#agrupar').val()
                }, function (d) {
                    $('#totalIngresos').text(d.totalIngresos);
                    $('#totalVehiculos').text(d.totalVehiculos);

                    // Tabla de ingresos y gráfico
                    var etiquetas = [];
                    var montos = [];
                    tablaIngresos.clear();
                    $.each(d.ingresos, function (i, f) {
                        etiquetas.push(f.periodo);
                        montos.push(f.total);
                        tablaIngresos.row.add([f.periodo, f.vehiculos, 'Bs ' + Number(f.total).toFixed(2)]);
                    });
                    tablaIngresos.draw();
                    graficoIngresos.data.labels = etiquetas;
                    graficoIngresos.data.datasets[0].data = montos;
                    graficoIngresos.update();

                    // Gráfico de entradas
                    var etiquetasEntradas = [];
                    var totales = [];
                    $.each(d.entradas, function (i, f) {
                        etiquetasEntradas.push(f.periodo);
                        totales.push(f.total);
                    });
                    graficoEntradas.data.labels = etiquetasEntradas;
                    graficoEntradas.data.datasets[0].data = totales;
                    graficoEntradas.update();

                    // Vehículos frecuentes
                    tablaFrecuentes.clear();
                    $.each(d.frecuentes, function (i, f) {
                        tablaFrecuentes.row.add([f.placa, f.tipo, f.veces]);
                    });
                    tablaFrecuentes.draw();
                }).fail(mostrarError);
            }

            // Ocupación actual: se actualiza cada 10 segundos
            function cargarOcupacion() {
                $.get("{{ route('reportes.ocupacion') }}", function (d) {
                    $('#ocupados').text(d.ocupados);
                    $('#totalEspacios').text(d.total);
                    $('#barraOcupacion').css('width', d.porcentaje + '%');
                });
            }

            $('#btnFiltrar').on('click', cargar);
            cargar();
            cargarOcupacion();
            setInterval(cargarOcupacion, 10000);
        });
    </script>
@endsection
