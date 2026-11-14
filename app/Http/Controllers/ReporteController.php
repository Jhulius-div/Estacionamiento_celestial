<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    // Pantalla de reportes
    public function index()
    {
        return view('reportes.reportes');
    }

    // Datos de los reportes según los filtros (se piden con jQuery)
    public function datos(Request $request)
    {
        $request->validate([
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
            'agrupar' => 'required|in:dia,mes',
        ]);

        $desde = $request->desde . ' 00:00:00';
        $hasta = $request->hasta . ' 23:59:59';
        $formato = $request->agrupar == 'mes' ? '%Y-%m' : '%Y-%m-%d';

        // Ingresos por día o por mes
        $ingresos = DB::table('boletos')
            ->selectRaw("DATE_FORMAT(hora_salida, '$formato') as periodo, COUNT(*) as vehiculos, SUM(monto_total) as total")
            ->where('esta_pagado', 1)
            ->whereBetween('hora_salida', [$desde, $hasta])
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();

        // Vehículos que ingresaron por día o por mes
        $entradas = DB::table('boletos')
            ->selectRaw("DATE_FORMAT(hora_entrada, '$formato') as periodo, COUNT(*) as total")
            ->whereBetween('hora_entrada', [$desde, $hasta])
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();

        // Vehículos más frecuentes
        $frecuentes = DB::table('boletos')
            ->join('vehiculos', 'vehiculos.id', '=', 'boletos.id_vehiculo')
            ->join('tipos_vehiculo', 'tipos_vehiculo.id', '=', 'vehiculos.id_tipo_vehiculo')
            ->select('vehiculos.placa', 'tipos_vehiculo.nombre as tipo', DB::raw('COUNT(*) as veces'))
            ->whereBetween('boletos.hora_entrada', [$desde, $hasta])
            ->groupBy('vehiculos.placa', 'tipos_vehiculo.nombre')
            ->orderByDesc('veces')
            ->limit(10)
            ->get();

        return response()->json([
            'ingresos' => $ingresos,
            'entradas' => $entradas,
            'frecuentes' => $frecuentes,
            'totalIngresos' => number_format($ingresos->sum('total'), 2),
            'totalVehiculos' => $ingresos->sum('vehiculos'),
        ]);
    }

    // Ocupación actual (se actualiza cada pocos segundos con jQuery)
    public function ocupacion()
    {
        $total = Espacio::where('estado', 1)->count();
        $ocupados = Espacio::where('estado', 1)->where('esta_ocupado', 1)->count();

        return response()->json([
            'total' => $total,
            'ocupados' => $ocupados,
            'libres' => $total - $ocupados,
            'porcentaje' => $total > 0 ? round($ocupados * 100 / $total) : 0,
        ]);
    }
}
