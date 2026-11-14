<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo') - {{ $config->nombre_estacionamiento }}</title>

    {{-- Estilos de la plantilla Celestial --}}
    <link rel="stylesheet" href="{{ asset('celestial/vendors/typicons.font/font/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('celestial/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('celestial/css/vertical-layout-light/style.css') }}">

    {{-- Estilos de DataTables --}}
    <link rel="stylesheet" href="{{ asset('librerias/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('librerias/buttons.bootstrap4.min.css') }}">

    <link rel="shortcut icon" href="{{ asset('celestial/images/favicon.png') }}">
</head>
<body>
<div class="container-scroller">

    {{-- Barra superior --}}
    @include('partials.navbar')

    <div class="container-fluid page-body-wrapper">

        {{-- Menú lateral --}}
        @include('partials.sidebar')

        <div class="main-panel">
            <div class="content-wrapper">
                @yield('contenido')
            </div>

            @include('partials.footer')
        </div>
    </div>
</div>

{{-- Scripts: jQuery y Bootstrap vienen en el bundle de la plantilla --}}
<script src="{{ asset('celestial/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('celestial/js/off-canvas.js') }}"></script>
<script src="{{ asset('celestial/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('celestial/js/template.js') }}"></script>

{{-- DataTables (con botones para exportar a Excel y PDF) --}}
<script src="{{ asset('librerias/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('librerias/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('librerias/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('librerias/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('librerias/jszip.min.js') }}"></script>
<script src="{{ asset('librerias/pdfmake.min.js') }}"></script>
<script src="{{ asset('librerias/vfs_fonts.js') }}"></script>
<script src="{{ asset('librerias/buttons.html5.min.js') }}"></script>

{{-- SweetAlert y funciones propias del sistema --}}
<script src="{{ asset('librerias/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/sistema.js') }}"></script>

@yield('scripts')
</body>
</html>
