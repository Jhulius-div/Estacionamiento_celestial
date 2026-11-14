@extends('layouts.app')

@section('titulo', 'Espacios')

@section('contenido')

    <div class="row">
        <div class="col-12 grid-margin">
            <h3 class="font-weight-bold">Espacios de estacionamiento</h3>
            <p class="text-muted mb-0">Pisos, sectores y celdas libres u ocupadas (se actualiza solo)</p>
        </div>
    </div>

    {{-- Mapa de celdas --}}
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Mapa de celdas</h4>
                    <p>
                        <span class="badge badge-success">Libre</span>
                        <span class="badge badge-danger">Ocupado</span>
                        <span class="badge badge-secondary">Inactivo</span>
                    </p>
                    <div id="mapa">
                        @include('espacios.mapa')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Listado de espacios</h4>
                        @if (auth()->user()->tieneRol('Administrador'))
                            <button type="button" class="btn btn-primary btn-nuevo">Añadir espacio +</button>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped" id="tabla">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Código</th>
                                    <th>Piso</th>
                                    <th>Sector</th>
                                    <th>Número</th>
                                    <th>Ocupación</th>
                                    <th>Estado</th>
                                    @if (auth()->user()->tieneRol('Administrador'))
                                        <th>Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="cuerpo">
                                @include('espacios.filas')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para añadir y editar (solo administrador) --}}
    @if (auth()->user()->tieneRol('Administrador'))
        <div class="modal fade" id="modalEspacio" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form id="formEspacio" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Espacio</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">

                        <div class="form-group">
                            <label>Piso</label>
                            <input type="number" name="piso" class="form-control" placeholder="1">
                        </div>
                        <div class="form-group">
                            <label>Sector</label>
                            <input type="text" name="sector" class="form-control" placeholder="A">
                        </div>
                        <div class="form-group">
                            <label>Número de celda</label>
                            <input type="number" name="numero" class="form-control" placeholder="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
    <script>
        $(function () {
            var urlMapa = "{{ route('espacios.mapa') }}";

            function cargarMapa() {
                $('#mapa').load(urlMapa);
            }

            @if (auth()->user()->tieneRol('Administrador'))
                iniciarCrud({
                    nombre: 'espacio',
                    url: "{{ url('/espacios') }}",
                    modal: '#modalEspacio',
                    form: '#formEspacio',
                    tabla: '#tabla',
                    cuerpo: '#cuerpo',
                    alRecargar: cargarMapa
                });
            @else
                crearTabla('#tabla');
            @endif

            // El mapa se actualiza solo cada 10 segundos
            setInterval(cargarMapa, 10000);
        });
    </script>
@endsection
