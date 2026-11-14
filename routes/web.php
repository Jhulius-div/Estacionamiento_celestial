<?php

use App\Http\Controllers\CajaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EspacioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OperacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

// Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'mostrar'])->name('login');
    Route::post('/login', [LoginController::class, 'entrar'])->name('login.entrar');
});

Route::post('/logout', [LoginController::class, 'salir'])->name('logout')->middleware('auth');

// Rutas que necesitan haber iniciado sesión
Route::middleware('auth')->group(function () {

    // Dashboard (todos los roles)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Ver espacios (todos los roles)
    Route::get('/espacios', [EspacioController::class, 'index'])->name('espacios.index');
    Route::get('/espacios/tabla', [EspacioController::class, 'tabla'])->name('espacios.tabla');
    Route::get('/espacios/mapa', [EspacioController::class, 'mapa'])->name('espacios.mapa');

    // Administrador y Operador
    Route::middleware('rol:Administrador,Operador')->group(function () {

        // Clientes
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/tabla', [ClienteController::class, 'tabla'])->name('clientes.tabla');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::post('/clientes/{cliente}/estado', [ClienteController::class, 'estado'])->name('clientes.estado');

        // Vehículos
        Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('vehiculos.index');
        Route::get('/vehiculos/tabla', [VehiculoController::class, 'tabla'])->name('vehiculos.tabla');
        Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.store');
        Route::put('/vehiculos/{vehiculo}', [VehiculoController::class, 'update'])->name('vehiculos.update');
        Route::post('/vehiculos/{vehiculo}/estado', [VehiculoController::class, 'estado'])->name('vehiculos.estado');

        // Entradas y salidas
        Route::get('/operacion', [OperacionController::class, 'index'])->name('operacion.index');
        Route::get('/operacion/tabla', [OperacionController::class, 'tabla'])->name('operacion.tabla');
        Route::get('/operacion/espacios-libres', [OperacionController::class, 'libres'])->name('operacion.libres');
        Route::get('/operacion/placa/{placa}', [OperacionController::class, 'buscarPlaca'])->name('operacion.placa');
        Route::post('/operacion/entrada', [OperacionController::class, 'entrada'])->name('operacion.entrada');
        Route::get('/operacion/{boleto}/cobro', [OperacionController::class, 'cobro'])->name('operacion.cobro');
        Route::post('/operacion/{boleto}/salida', [OperacionController::class, 'salida'])->name('operacion.salida');
        Route::get('/operacion/{boleto}/ticket', [OperacionController::class, 'ticket'])->name('operacion.ticket');

        // Caja
        Route::get('/caja', [CajaController::class, 'index'])->name('caja.index');
        Route::get('/caja/datos', [CajaController::class, 'datos'])->name('caja.datos');
    });

    // Administrador y Supervisor
    Route::middleware('rol:Administrador,Supervisor')->group(function () {
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/datos', [ReporteController::class, 'datos'])->name('reportes.datos');
        Route::get('/reportes/ocupacion', [ReporteController::class, 'ocupacion'])->name('reportes.ocupacion');
    });

    // Solo Administrador
    Route::middleware('rol:Administrador')->group(function () {

        // Espacios
        Route::post('/espacios', [EspacioController::class, 'store'])->name('espacios.store');
        Route::put('/espacios/{espacio}', [EspacioController::class, 'update'])->name('espacios.update');
        Route::post('/espacios/{espacio}/estado', [EspacioController::class, 'estado'])->name('espacios.estado');

        // Usuarios
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/tabla', [UsuarioController::class, 'tabla'])->name('usuarios.tabla');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::post('/usuarios/{usuario}/estado', [UsuarioController::class, 'estado'])->name('usuarios.estado');

        // Tarifas
        Route::get('/tarifas', [TarifaController::class, 'index'])->name('tarifas.index');
        Route::get('/tarifas/tabla', [TarifaController::class, 'tabla'])->name('tarifas.tabla');
        Route::put('/tarifas/{tipo}', [TarifaController::class, 'update'])->name('tarifas.update');

        // Configuración (nombre y logo)
        Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::post('/configuracion', [ConfiguracionController::class, 'guardar'])->name('configuracion.guardar');
    });
});
