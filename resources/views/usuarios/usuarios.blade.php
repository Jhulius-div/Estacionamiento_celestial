@extends('layouts.app')

@section('titulo', 'Usuarios')

@section('contenido')

    <div class="row">
        <div class="col-12 grid-margin">
            <h3 class="font-weight-bold">Usuarios</h3>
            <p class="text-muted mb-0">Gestión de usuarios y asignación de roles</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Listado de usuarios</h4>
                        <button type="button" class="btn btn-primary btn-nuevo">Añadir usuario +</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped" id="tabla">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo">
                                @include('usuarios.filas')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para añadir y editar --}}
    <div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="formUsuario" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre completo">
                    </div>
                    <div class="form-group">
                        <label>Correo</label>
                        <input type="text" name="correo" class="form-control" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" autocomplete="new-password">
                        <small class="text-muted">Al editar, déjala vacía para no cambiarla.</small>
                    </div>
                    <div class="form-group">
                        <label>Rol</label>
                        <select name="id_rol" class="form-control">
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
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
                nombre: 'usuario',
                url: "{{ url('/usuarios') }}",
                modal: '#modalUsuario',
                form: '#formUsuario',
                tabla: '#tabla',
                cuerpo: '#cuerpo'
            });
        });
    </script>
@endsection
