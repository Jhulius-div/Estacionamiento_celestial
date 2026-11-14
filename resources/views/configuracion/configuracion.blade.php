@extends('layouts.app')

@section('titulo', 'Configuración')

@section('contenido')

    <div class="row">
        <div class="col-12 grid-margin">
            <h3 class="font-weight-bold">Configuración</h3>
            <p class="text-muted mb-0">Nombre y logo del estacionamiento (se ven en el login, el menú y el ticket)</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Datos del estacionamiento</h4>

                    <form id="formConfiguracion" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nombre del estacionamiento</label>
                            <input type="text" name="nombre_estacionamiento" class="form-control" value="{{ $configuracion->nombre_estacionamiento }}">
                        </div>

                        <div class="form-group">
                            <label>Logo</label>
                            <div class="mb-2">
                                <img id="vistaPrevia" src="{{ $configuracion->logo_url }}" alt="logo" style="max-height: 90px; width: auto; border: 1px solid #ddd; padding: 5px;">
                            </div>
                            <input type="file" name="logo" id="logo" class="form-control" accept="image/png,image/jpeg,image/webp">
                            <small class="text-muted">PNG, JPG o WEBP de hasta 2 MB.</small>
                        </div>

                        @if ($configuracion->logo)
                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="quitar_logo" class="form-check-input"> Quitar el logo actual
                                    </label>
                                </div>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {

            // Muestra la imagen elegida antes de guardarla
            $('#logo').on('change', function () {
                var archivo = this.files[0];

                if (archivo) {
                    var lector = new FileReader();
                    lector.onload = function (e) {
                        $('#vistaPrevia').attr('src', e.target.result);
                    };
                    lector.readAsDataURL(archivo);
                }
            });

            // Guardar con AJAX (se envía con FormData porque lleva un archivo)
            $('#formConfiguracion').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('configuracion.guardar') }}",
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function (resp) {
                        Swal.fire('¡Buen trabajo!', resp.mensaje, 'success').then(function () {
                            location.reload();
                        });
                    },
                    error: mostrarError
                });
            });
        });
    </script>
@endsection
