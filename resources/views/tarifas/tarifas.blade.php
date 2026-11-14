@extends('layouts.app')

@section('titulo', 'Tarifas')

@section('contenido')

    <div class="row">
        <div class="col-12 grid-margin">
            <h3 class="font-weight-bold">Tarifas</h3>
            <p class="text-muted mb-0">Precio por hora de cada tipo de vehículo. Se cobra por hora o fracción (mínimo 1 hora).</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tarifas por tipo de vehículo</h4>

                    <div class="table-responsive">
                        <table class="table table-striped" id="tabla">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Tipo de vehículo</th>
                                    <th>Tarifa por hora (Bs)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo">
                                @include('tarifas.filas')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para editar --}}
    <div class="modal fade" id="modalTarifa" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="formTarifa" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tarifa</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">

                    <div class="form-group">
                        <label>Tipo de vehículo</label>
                        <input type="text" name="nombre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Tarifa por hora (Bs)</label>
                        <input type="number" step="0.01" name="tarifa_hora" class="form-control" placeholder="0.00">
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
                nombre: 'tarifa',
                url: "{{ url('/tarifas') }}",
                modal: '#modalTarifa',
                form: '#formTarifa',
                tabla: '#tabla',
                cuerpo: '#cuerpo'
            });
        });
    </script>
@endsection
