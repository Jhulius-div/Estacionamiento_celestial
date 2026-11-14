<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\Espacio;
use App\Models\MetodoPago;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use App\Support\Tarifa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperacionController extends Controller
{
    // Pantalla de entradas y salidas
    public function index()
    {
        $tipos = TipoVehiculo::orderBy('id')->get();
        $metodos = MetodoPago::orderBy('id')->get();
        $espacios = $this->espaciosLibres();
        $boletos = $this->vehiculosDentro();

        return view('operacion.operacion', compact('tipos', 'metodos', 'espacios', 'boletos'));
    }

    // Filas de los vehículos que están dentro (se recargan con jQuery)
    public function tabla()
    {
        $boletos = $this->vehiculosDentro();

        return view('operacion.filas', compact('boletos'));
    }

    // Opciones del select con los espacios libres
    public function libres()
    {
        $espacios = $this->espaciosLibres();

        return view('operacion.opciones', compact('espacios'));
    }

    // Busca una placa para completar el formulario automáticamente
    public function buscarPlaca($placa)
    {
        $vehiculo = Vehiculo::with('cliente')->where('placa', strtoupper($placa))->first();

        if (! $vehiculo) {
            return response()->json(['existe' => false]);
        }

        $dentro = Boleto::where('id_vehiculo', $vehiculo->id)->whereNull('hora_salida')->exists();

        return response()->json([
            'existe' => true,
            'id_tipo_vehiculo' => $vehiculo->id_tipo_vehiculo,
            'marca' => $vehiculo->marca,
            'modelo' => $vehiculo->modelo,
            'color' => $vehiculo->color,
            'cliente' => $vehiculo->cliente->nombre ?? null,
            'activo' => (bool) $vehiculo->estado,
            'dentro' => $dentro,
        ]);
    }

    // Registrar la entrada de un vehículo
    public function entrada(Request $request)
    {
        $request->validate([
            'placa' => 'required|string|max:15',
            'id_tipo_vehiculo' => 'required|exists:tipos_vehiculo,id',
            'id_espacio' => 'required|exists:espacios_estacionamiento,id',
            'marca' => 'nullable|string|max:50',
            'modelo' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
        ]);

        $placa = strtoupper(trim($request->placa));
        $espacio = Espacio::find($request->id_espacio);
        $vehiculo = Vehiculo::where('placa', $placa)->first();

        if (! $espacio->estado || $espacio->esta_ocupado) {
            return response()->json(['mensaje' => 'El espacio seleccionado no está disponible.'], 422);
        }

        if ($vehiculo && ! $vehiculo->estado) {
            return response()->json(['mensaje' => 'Este vehículo está desactivado.'], 422);
        }

        if ($vehiculo && Boleto::where('id_vehiculo', $vehiculo->id)->whereNull('hora_salida')->exists()) {
            return response()->json(['mensaje' => 'Este vehículo ya está dentro del estacionamiento.'], 422);
        }

        // Si la placa no existe se registra el vehículo
        if (! $vehiculo) {
            $vehiculo = Vehiculo::create([
                'placa' => $placa,
                'id_tipo_vehiculo' => $request->id_tipo_vehiculo,
                'marca' => $request->marca,
                'modelo' => $request->modelo,
                'color' => $request->color,
            ]);
        }

        $boleto = DB::transaction(function () use ($vehiculo, $espacio) {
            $boleto = Boleto::create([
                'id_vehiculo' => $vehiculo->id,
                'id_espacio_estacionamiento' => $espacio->id,
                'id_usuario' => auth()->id(),
                'hora_entrada' => now(),
            ]);

            $espacio->update(['esta_ocupado' => true]);

            return $boleto;
        });

        return response()->json([
            'mensaje' => 'Entrada registrada. Ticket N° ' . $boleto->id,
            'ticket' => route('operacion.ticket', $boleto->id),
        ]);
    }

    // Calcula el tiempo y el monto antes de cobrar
    public function cobro(Boleto $boleto)
    {
        if ($boleto->hora_salida) {
            return response()->json(['mensaje' => 'Este boleto ya fue cobrado.'], 422);
        }

        $salida = now();
        $minutos = Tarifa::minutos($boleto->hora_entrada, $salida);
        $tarifaHora = $boleto->vehiculo->tipo->tarifa_hora;

        return response()->json([
            'placa' => $boleto->vehiculo->placa,
            'espacio' => $boleto->espacio->codigo,
            'entrada' => $boleto->hora_entrada->format('d/m/Y H:i'),
            'salida' => $salida->format('d/m/Y H:i'),
            'tiempo' => intdiv($minutos, 60) . ' h ' . ($minutos % 60) . ' min',
            'tarifa_hora' => number_format($tarifaHora, 2),
            'monto' => number_format(Tarifa::calcular($minutos, $tarifaHora), 2),
        ]);
    }

    // Registrar la salida y el cobro (efectivo o QR)
    public function salida(Request $request, Boleto $boleto)
    {
        $request->validate(['id_metodo_pago' => 'required|exists:metodos_pago,id']);

        if ($boleto->hora_salida) {
            return response()->json(['mensaje' => 'Este boleto ya fue cobrado.'], 422);
        }

        $salida = now();
        $minutos = Tarifa::minutos($boleto->hora_entrada, $salida);
        $monto = Tarifa::calcular($minutos, $boleto->vehiculo->tipo->tarifa_hora);

        DB::transaction(function () use ($boleto, $request, $salida, $minutos, $monto) {
            $boleto->update([
                'hora_salida' => $salida,
                'total_tiempo_minutos' => $minutos,
                'id_metodo_pago' => $request->id_metodo_pago,
                'monto_total' => $monto,
                'esta_pagado' => true,
            ]);

            $boleto->espacio->update(['esta_ocupado' => false]);
        });

        return response()->json([
            'mensaje' => 'Cobro registrado: Bs ' . number_format($monto, 2),
            'ticket' => route('operacion.ticket', $boleto->id),
        ]);
    }

    // Ticket digital (sirve para imprimir)
    public function ticket(Boleto $boleto)
    {
        $boleto->load('vehiculo.tipo', 'espacio', 'usuario', 'metodoPago');

        return view('operacion.ticket', compact('boleto'));
    }

    private function espaciosLibres()
    {
        return Espacio::where('estado', 1)
            ->where('esta_ocupado', 0)
            ->orderBy('piso')->orderBy('sector')->orderBy('numero')
            ->get();
    }

    private function vehiculosDentro()
    {
        return Boleto::with('vehiculo.tipo', 'espacio')
            ->whereNull('hora_salida')
            ->orderBy('hora_entrada')
            ->get();
    }
}
