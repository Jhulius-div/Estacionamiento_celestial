<?php

namespace Database\Seeders;

use App\Models\Boleto;
use App\Models\Cliente;
use App\Models\Configuracion;
use App\Models\Espacio;
use App\Models\MetodoPago;
use App\Models\Rol;
use App\Models\TipoVehiculo;
use App\Models\Usuario;
use App\Models\Vehiculo;
use App\Support\Tarifa;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        Rol::insert([['nombre' => 'Administrador'], ['nombre' => 'Operador'], ['nombre' => 'Supervisor']]);

        // Usuarios
        Usuario::create(['nombre' => 'Administrador', 'correo' => 'admin@estacionamiento.com', 'password' => 'admin123', 'id_rol' => 1]);
        Usuario::create(['nombre' => 'Carlos Operador', 'correo' => 'operador@estacionamiento.com', 'password' => 'operador123', 'id_rol' => 2]);
        Usuario::create(['nombre' => 'Sofía Supervisora', 'correo' => 'supervisor@estacionamiento.com', 'password' => 'supervisor123', 'id_rol' => 3]);

        // Tipos de vehículo con su tarifa por hora (Bs)
        TipoVehiculo::insert([
            ['nombre' => 'Auto', 'tarifa_hora' => 5],
            ['nombre' => 'Moto', 'tarifa_hora' => 2],
            ['nombre' => 'Camioneta', 'tarifa_hora' => 7],
        ]);

        // Métodos de pago
        MetodoPago::insert([['nombre' => 'Efectivo'], ['nombre' => 'QR']]);

        // Espacios: 2 pisos, sectores A y B, 4 celdas cada uno
        foreach ([1, 2] as $piso) {
            foreach (['A', 'B'] as $sector) {
                for ($numero = 1; $numero <= 4; $numero++) {
                    Espacio::create(['piso' => $piso, 'sector' => $sector, 'numero' => $numero]);
                }
            }
        }

        // Configuración del sistema
        Configuracion::create(['nombre_estacionamiento' => 'Estacionamiento Central']);

        // Clientes
        $juan = Cliente::create(['nombre' => 'Juan Pérez', 'telefono' => '70112233', 'correo' => 'juan@correo.com', 'es_suscriptor' => true, 'fecha_vencimiento' => now()->addMonth()->toDateString()]);
        $maria = Cliente::create(['nombre' => 'María Gómez', 'telefono' => '71445566', 'correo' => 'maria@correo.com']);

        // Vehículos
        Vehiculo::create(['placa' => '1234-ABC', 'id_cliente' => $juan->id, 'id_tipo_vehiculo' => 1, 'marca' => 'Toyota', 'modelo' => 'Yaris', 'color' => 'Rojo']);
        Vehiculo::create(['placa' => '4521-XYZ', 'id_cliente' => $maria->id, 'id_tipo_vehiculo' => 2, 'marca' => 'Honda', 'modelo' => 'CB125', 'color' => 'Negro']);
        Vehiculo::create(['placa' => '3300-KLM', 'id_tipo_vehiculo' => 3, 'marca' => 'Nissan', 'modelo' => 'Frontier', 'color' => 'Blanco']);
        Vehiculo::create(['placa' => '2345-QWE', 'id_cliente' => $juan->id, 'id_tipo_vehiculo' => 1, 'marca' => 'Suzuki', 'modelo' => 'Swift', 'color' => 'Gris']);

        // Boletos de ejemplo ya cobrados: vehículo, espacio, días atrás, minutos, método de pago
        $ejemplos = [
            [1, 1, 6, 45, 1], [2, 2, 6, 130, 2], [3, 3, 5, 95, 1], [1, 1, 4, 200, 1], [4, 4, 3, 60, 2],
            [2, 2, 2, 30, 1], [1, 1, 2, 310, 1], [3, 3, 1, 150, 2], [4, 4, 1, 75, 1], [1, 1, 0, 90, 1],
        ];

        foreach ($ejemplos as [$vehiculo, $espacio, $dias, $minutos, $pago]) {
            $entrada = now()->subDays($dias)->subMinutes($minutos + 30);
            $tarifa = Vehiculo::find($vehiculo)->tipo->tarifa_hora;

            Boleto::create([
                'id_vehiculo' => $vehiculo,
                'id_espacio_estacionamiento' => $espacio,
                'id_usuario' => 2,
                'hora_entrada' => $entrada,
                'hora_salida' => $entrada->copy()->addMinutes($minutos),
                'total_tiempo_minutos' => $minutos,
                'id_metodo_pago' => $pago,
                'monto_total' => Tarifa::calcular($minutos, $tarifa),
                'esta_pagado' => true,
            ]);
        }

        // Un vehículo que está dentro del estacionamiento ahora
        Boleto::create([
            'id_vehiculo' => 2,
            'id_espacio_estacionamiento' => 5,
            'id_usuario' => 2,
            'hora_entrada' => now()->subMinutes(40),
        ]);
        Espacio::find(5)->update(['esta_ocupado' => true]);
    }
}
