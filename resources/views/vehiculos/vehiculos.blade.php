@extends('layouts.app')

@section('titulo', 'Vehículos')

@section('contenido')

    <div class="row">
        <div class="col-12 grid-margin">
            <h3 class="font-weight-bold">Vehículos</h3>
            <p class="text-muted mb-0">Registro de vehículos (placa, marca, modelo, color y tipo)</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Listado de vehículos</h4>
                        <button type="button" class="btn btn-primary btn-nuevo">Añadir vehículo +</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped" id="tabla">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Placa</th>
                                    <th>Tipo</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Color</th>
                                    <th>Cliente</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo">
                                @include('vehiculos.filas')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para añadir y editar --}}
    <div class="modal fade" id="modalVehiculo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="formVehiculo" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vehículo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">

                    <div class="form-group">
                        <label>Placa</label>
                        <input type="text" name="placa" class="form-control" placeholder="1234-ABC">
                    </div>
                    <div class="form-group">
                        <label>Tipo de vehículo</label>
                        <select name="id_tipo_vehiculo" class="form-control">
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cliente (opcional)</label>
                        <select name="id_cliente" class="form-control">
                            <option value="">Sin cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control" placeholder="Marca">
                    </div>
                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control" placeholder="Modelo">
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <input type="text" name="color" class="form-control" placeholder="Color">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {
            iniciarCrud({
                nombre: 'vehículo',
                url: "{{ url('/vehiculos') }}",
                modal: '#modalVehiculo',
                form: '#formVehiculo',
                tabla: '#tabla',
                cuerpo: '#cuerpo'
            });
        });
    </script>
@endsection
