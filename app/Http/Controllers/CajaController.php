<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    // Pantalla de caja
    public function index()
    {
        return view('caja.caja');
    }

    // Transacciones cobradas entre dos fechas (se pide con jQuery)
    public function datos(Request $request)
    {
        $request->validate([
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
        ]);

        $boletos = Boleto::with('vehiculo', 'metodoPago', 'usuario')
            ->where('esta_pagado', 1)
            ->whereBetween('hora_salida', [$request->desde . ' 00:00:00', $request->hasta . ' 23:59:59'])
            ->orderByDesc('hora_salida')
            ->get();

        $filas = [];
        foreach ($boletos as $b) {
            $filas[] = [
                $b->id,
                $b->vehiculo->placa,
                $b->hora_entrada->format('d/m/Y H:i'),
                $b->hora_salida->format('d/m/Y H:i'),
                $b->total_tiempo_minutos . ' min',
                $b->metodoPago->nombre,
                'Bs ' . number_format($b->monto_total, 2),
                $b->usuario->nombre,
            ];
        }

        $efectivo = $boletos->filter(fn ($b) => $b->metodoPago->nombre == 'Efectivo')->sum('monto_total');
        $qr = $boletos->filter(fn ($b) => $b->metodoPago->nombre == 'QR')->sum('monto_total');

        return response()->json([
            'filas' => $filas,
            'cantidad' => $boletos->count(),
            'total' => number_format($boletos->sum('monto_total'), 2),
            'efectivo' => number_format($efectivo, 2),
            'qr' => number_format($qr, 2),
        ]);
    }
}
