<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Iniciar sesión - {{ $config->nombre_estacionamiento }}</title>

    <link rel="stylesheet" href="{{ asset('celestial/vendors/typicons.font/font/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('celestial/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('celestial/css/vertical-layout-light/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('celestial/images/favicon.png') }}">
</head>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5">

                        <div class="brand-logo">
                            <img src="{{ $config->logo_url }}" alt="logo" style="max-height: 70px; width: auto;">
                        </div>
                        <h4>{{ $config->nombre_estacionamiento }}</h4>
                        <h6 class="font-weight-light">Inicia sesión para continuar.</h6>

                        <form class="pt-3" method="POST" action="{{ route('login.entrar') }}">
                            @csrf
                            <div class="form-group">
                                <input type="email" name="correo" class="form-control form-control-lg" placeholder="Correo" value="{{ old('correo') }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="Contraseña" required>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">INGRESAR</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('celestial/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('librerias/sweetalert2.all.min.js') }}"></script>

{{-- Mensaje de error del login --}}
@if ($errors->any())
    <script>
        Swal.fire({ icon: 'error', title: 'No se pudo ingresar', text: @json($errors->first()) });
    </script>
@endif
</body>
</html>
