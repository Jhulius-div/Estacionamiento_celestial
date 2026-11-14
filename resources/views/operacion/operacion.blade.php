@extends('layouts.app')

@section('titulo', 'Entradas y salidas')

@section('contenido')

    <div class="row">
        <div class="col-12 grid-margin">
            <h3 class="font-weight-bold">Entradas y salidas</h3>
            <p class="text-muted mb-0">Registra la entrada de un vehículo y cobra su salida</p>
        </div>
    </div>

    <div class="row">

        {{-- Registrar entrada --}}
        <div class="col-lg-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registrar entrada</h4>

                    <form id="formEntrada">
                        <div class="form-group">
                            <label>Placa</label>
                            <input type="text" name="placa" id="placa" class="form-control" placeholder="1234-ABC" maxlength="15" style="text-transform: uppercase;">
                            <small id="infoPlaca"></small>
                        </div>
                        <div class="form-group">
                            <label>Tipo de vehículo</label>
                            <select name="id_tipo_vehiculo" id="id_tipo_vehiculo" class="form-control">
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Marca</label>
                            <input type="text" name="marca" id="marca" class="form-control" placeholder="Marca">
                        </div>
                        <div class="form-group">
                            <label>Modelo</label>
                            <input type="text" name="modelo" id="modelo" class="form-control" placeholder="Modelo">
                        </div>
                        <div class="form-group">
                            <label>Color</label>
                            <input type="text" name="color" id="color" class="form-control" placeholder="Color">
                        </div>
                        <div class="form-group">
                            <label>Espacio</label>
                            <select name="id_espacio" id="id_espacio" class="form-control">
                                @include('operacion.opciones')
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Registrar entrada</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Vehículos dentro --}}
        <div class="col-lg-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Vehículos dentro del estacionamiento</h4>

                    <div class="table-responsive">
                        <table class="table table-striped" id="tabla">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Placa</th>
                                    <th>Tipo</th>
                                    <th>Espacio</th>
                                    <th>Entrada</th>
                                    <th>Tiempo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo">
                                @include('operacion.filas')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de cobro --}}
    <div class="modal fade" id="modalCobro" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="formCobro" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cobrar salida</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idBoleto">

                    <table class="table table-sm">
                        <tr><td>Placa</td><td id="cobroPlaca"></td></tr>
                        <tr><td>Espacio</td><td id="cobroEspacio"></td></tr>
                        <tr><td>Entrada</td><td id="cobroEntrada"></td></tr>
                        <tr><td>Salida</td><td id="cobroSalida"></td></tr>
                        <tr><td>Tiempo</td><td id="cobroTiempo"></td></tr>
                        <tr><td>Tarifa por hora</td><td>Bs <span id="cobroTarifa"></span></td></tr>
                    </table>

                    <h3 class="text-center my-3">Total: Bs <span id="cobroMonto" class="font-weight-bold text-primary"></span></h3>

                    <div class="form-group">
                        <label>Método de pago</label>
                        <select name="id_metodo_pago" id="id_metodo_pago" class="form-control">
                            @foreach ($metodos as $metodo)
                                <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Confirmar cobro</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {
            var url = "{{ url('/operacion') }}";
            var tabla = crearTabla('#tabla');

            // Recargar la tabla y la lista de espacios libres
            function recargar() {
                tabla.destroy();
                $('#cuerpo').load(url + '/tabla', function () {
                    tabla = crearTabla('#tabla');
                });
                $('#id_espacio').load(url + '/espacios-libres');
            }

            // Mensaje con botón para abrir el ticket
            function mensajeTicket(resp, boton) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Buen trabajo!',
                    text: resp.mensaje,
                    showCancelButton: true,
                    confirmButtonText: boton,
                    cancelButtonText: 'Cerrar'
                }).then(function (resultado) {
                    if (resultado.isConfirmed) window.open(resp.ticket, '_blank');
                });
            }

            // Buscar la placa y completar los datos
            $('#placa').on('blur', function () {
                var placa = $.trim($(this).val());
                if (placa === '') return;

                $.get(url + '/placa/' + encodeURIComponent(placa), function (v) {
                    var info = $('#infoPlaca');

                    if (!v.existe) {
                        info.attr('class', 'text-muted').text('Vehículo nuevo: completa los datos.');
                        return;
                    }

                    $('#id_tipo_vehiculo').val(v.id_tipo_vehiculo);
                    $('#marca').val(v.marca);
                    $('#modelo').val(v.modelo);
                    $('#color').val(v.color);

                    if (!v.activo) {
                        info.attr('class', 'text-danger').text('Este vehículo está desactivado.');
                    } else if (v.dentro) {
                        info.attr('class', 'text-danger').text('Este vehículo ya está dentro.');
                    } else {
                        info.attr('class', 'text-success').text('Vehículo registrado' + (v.cliente ? ' - Cliente: ' + v.cliente : ''));
                    }
                });
            });

            // Registrar entrada
            $('#formEntrada').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: url + '/entrada',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (resp) {
                        $('#formEntrada')[0].reset();
                        $('#infoPlaca').text('');
                        recargar();
                        mensajeTicket(resp, 'Ver ticket');
                    },
                    error: mostrarError
                });
            });

            // Abrir el cobro: pide al servidor el tiempo y el monto
            $(document).on('click', '.btn-cobrar', function () {
                var id = $(this).data('id');

                $.get(url + '/' + id + '/cobro', function (d) {
                    $('#idBoleto').val(id);
                    $('#cobroPlaca').text(d.placa);
                    $('#cobroEspacio').text(d.espacio);
                    $('#cobroEntrada').text(d.entrada);
                    $('#cobroSalida').text(d.salida);
                    $('#cobroTiempo').text(d.tiempo);
                    $('#cobroTarifa').text(d.tarifa_hora);
                    $('#cobroMonto').text(d.monto);
                    $('#modalCobro').modal('show');
                }).fail(mostrarError);
            });

            // Confirmar el cobro
            $('#formCobro').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: url + '/' + $('#idBoleto').val() + '/salida',
                    type: 'POST',
                    data: { id_metodo_pago: $('#id_metodo_pago').val() },
                    success: function (resp) {
                        $('#modalCobro').modal('hide');
                        recargar();
                        mensajeTicket(resp, 'Ver comprobante');
                    },
                    error: mostrarError
                });
            });
        });
    </script>
@endsection
