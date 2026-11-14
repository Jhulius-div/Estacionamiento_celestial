<?php

namespace App\Http\Controllers;

use App\Models\TipoVehiculo;
use Illuminate\Http\Request;

class TarifaController extends Controller
{
    // Tarifa por hora de cada tipo de vehículo
    public function index()
    {
        $tipos = TipoVehiculo::orderBy('id')->get();

        return view('tarifas.tarifas', compact('tipos'));
    }

    public function tabla()
    {
        $tipos = TipoVehiculo::orderBy('id')->get();

        return view('tarifas.filas', compact('tipos'));
    }

    public function update(Request $request, TipoVehiculo $tipo)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:50',
            'tarifa_hora' => 'required|numeric|min:0|max:9999',
        ]);

        $tipo->update($datos);

        return response()->json(['mensaje' => 'Tarifa actualizada con éxito.']);
    }
}
