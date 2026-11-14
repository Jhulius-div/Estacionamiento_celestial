@extends('layouts.app')

@section('titulo', 'Clientes')

@section('contenido')

    <div class="row">
        <div class="col-12 grid-margin">
            <h3 class="font-weight-bold">Clientes</h3>
            <p class="text-muted mb-0">Clientes habituales y abonados (mensualidades)</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Listado de clientes</h4>
                        <button type="button" class="btn btn-primary btn-nuevo">Añadir cliente +</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped" id="tabla">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Suscriptor</th>
                                    <th>Vence</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo">
                                @include('clientes.filas')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para añadir y editar --}}
    <div class="modal fade" id="modalCliente" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="formCliente" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre completo">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control" placeholder="Teléfono">
                    </div>
                    <div class="form-group">
                        <label>Correo</label>
                        <input type="text" name="correo" class="form-control" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" name="es_suscriptor" class="form-check-input"> Es suscriptor (abonado mensual)
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Fecha de vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control">
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
                nombre: 'cliente',
                url: "{{ url('/clientes') }}",
                modal: '#modalCliente',
                form: '#formCliente',
                tabla: '#tabla',
                cuerpo: '#cuerpo'
            });
        });
    </script>
@endsection
