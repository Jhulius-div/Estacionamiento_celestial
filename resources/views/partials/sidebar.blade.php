@php
    $usuario = auth()->user();
@endphp

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        {{-- Perfil --}}
        <li class="nav-item">
            <div class="d-flex sidebar-profile">
                <div class="sidebar-profile-image">
                    <img src="{{ asset('celestial/images/faces/face29.png') }}" alt="image">
                    <span class="sidebar-status-indicator"></span>
                </div>
                <div class="sidebar-profile-name">
                    <p class="sidebar-name">{{ $usuario->nombre }}</p>
                    <p class="sidebar-designation">{{ $usuario->rol->nombre }}</p>
                </div>
            </div>
            <p class="sidebar-menu-title">Menú</p>
        </li>

        {{-- Dashboard --}}
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="typcn typcn-device-desktop menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        {{-- Tablas --}}
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#tablas" aria-expanded="false" aria-controls="tablas">
                <i class="typcn typcn-th-small-outline menu-icon"></i>
                <span class="menu-title">Tablas</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tablas">
                <ul class="nav flex-column sub-menu">
                    @if ($usuario->tieneRol('Administrador', 'Operador'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('clientes.index') }}">Clientes</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('vehiculos.index') }}">Vehículos</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="{{ route('espacios.index') }}">Espacios</a></li>
                    @if ($usuario->tieneRol('Administrador'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('usuarios.index') }}">Usuarios</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('tarifas.index') }}">Tarifas</a></li>
                    @endif
                </ul>
            </div>
        </li>

        {{-- Operación --}}
        @if ($usuario->tieneRol('Administrador', 'Operador'))
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#operacion" aria-expanded="false" aria-controls="operacion">
                    <i class="typcn typcn-arrow-sync menu-icon"></i>
                    <span class="menu-title">Operación</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="operacion">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"><a class="nav-link" href="{{ route('operacion.index') }}">Entradas y salidas</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('caja.index') }}">Caja</a></li>
                    </ul>
                </div>
            </li>
        @endif

        {{-- Reportes --}}
        @if ($usuario->tieneRol('Administrador', 'Supervisor'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reportes.index') }}">
                    <i class="typcn typcn-chart-pie-outline menu-icon"></i>
                    <span class="menu-title">Reportes</span>
                </a>
            </li>
        @endif

        {{-- Configuración --}}
        @if ($usuario->tieneRol('Administrador'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('configuracion.index') }}">
                    <i class="typcn typcn-cog-outline menu-icon"></i>
                    <span class="menu-title">Configuración</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
