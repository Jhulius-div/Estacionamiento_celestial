<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\Espacio;

class DashboardController extends Controller
{
    public function index()
    {
        $libres = Espacio::where('estado', 1)->where('esta_ocupado', 0)->count();
        $ocupados = Espacio::where('estado', 1)->where('esta_ocupado', 1)->count();
        $entradasHoy = Boleto::whereDate('hora_entrada', today())->count();
        $ingresosHoy = Boleto::whereDate('hora_salida', today())->sum('monto_total');

        // Ingresos de los últimos 7 días para el gráfico
        $dias = [];
        $montos = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = today()->subDays($i);
            $dias[] = $fecha->format('d/m');
            $montos[] = (float) Boleto::whereDate('hora_salida', $fecha)->sum('monto_total');
        }

        $ultimos = Boleto::with('vehiculo', 'espacio')->latest('id')->take(5)->get();

        return view('dashboard', compact('libres', 'ocupados', 'entradasHoy', 'ingresosHoy', 'dias', 'montos', 'ultimos'));
    }
}
