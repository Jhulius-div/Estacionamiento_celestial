@extends('layouts.app')

@section('titulo', 'Caja')

@section('contenido')

    <div class="row">
        <div class="col-12 grid-margin">
            <h3 class="font-weight-bold">Caja</h3>
            <p class="text-muted mb-0">Transacciones cobradas en efectivo o QR</p>
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
                            <input type="date" id="desde" class="form-control" value="{{ today()->toDateString() }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Hasta</label>
                            <input type="date" id="hasta" class="form-control" value="{{ today()->toDateString() }}">
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
        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card"><div class="card-body">
                <p class="card-title text-muted mb-1">Total cobrado</p>
                <h2 class="font-weight-bold text-primary mb-0">Bs <span id="total">0.00</span></h2>
            </div></div>
        </div>
        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card"><div class="card-body">
                <p class="card-title text-muted mb-1">Efectivo</p>
                <h2 class="font-weight-bold text-success mb-0">Bs <span id="efectivo">0.00</span></h2>
            </div></div>
        </div>
        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card"><div class="card-body">
                <p class="card-title text-muted mb-1">QR</p>
                <h2 class="font-weight-bold text-info mb-0">Bs <span id="qr">0.00</span></h2>
            </div></div>
        </div>
        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card"><div class="card-body">
                <p class="card-title text-muted mb-1">Transacciones</p>
                <h2 class="font-weight-bold text-warning mb-0"><span id="cantidad">0</span></h2>
            </div></div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Transacciones</h4>

                    <div class="table-responsive">
                        <table class="table table-striped" id="tabla">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Placa</th>
                                    <th>Entrada</th>
                                    <th>Salida</th>
                                    <th>Tiempo</th>
                                    <th>Método</th>
                                    <th>Monto</th>
                                    <th>Cajero</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {
            var tabla = crearTabla('#tabla', true, 'Transacciones de caja');

            // Pide las transacciones al servidor con las fechas elegidas
            function cargar() {
                $.get("{{ route('caja.datos') }}", { desde: $('#desde').val(), hasta: $('#hasta').val() }, function (d) {
                    $('#total').text(d.total);
                    $('#efectivo').text(d.efectivo);
                    $('#qr').text(d.qr);
                    $('#cantidad').text(d.cantidad);

                    tabla.clear().rows.add(d.filas).draw();
                }).fail(mostrarError);
            }

            $('#btnFiltrar').on('click', cargar);
            cargar();
        });
    </script>
@endsection
