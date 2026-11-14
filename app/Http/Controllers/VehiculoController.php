<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\Cliente;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehiculoController extends Controller
{
    // Pantalla con la tabla de vehículos
    public function index()
    {
        $vehiculos = Vehiculo::with('cliente', 'tipo')->orderBy('id')->get();
        $clientes = Cliente::where('estado', 1)->orderBy('nombre')->get();
        $tipos = TipoVehiculo::orderBy('id')->get();

        return view('vehiculos.vehiculos', compact('vehiculos', 'clientes', 'tipos'));
    }

    public function tabla()
    {
        $vehiculos = Vehiculo::with('cliente', 'tipo')->orderBy('id')->get();

        return view('vehiculos.filas', compact('vehiculos'));
    }

    public function store(Request $request)
    {
        Vehiculo::create($this->validar($request));

        return response()->json(['mensaje' => 'Vehículo registrado con éxito.']);
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        $vehiculo->update($this->validar($request, $vehiculo->id));

        return response()->json(['mensaje' => 'Vehículo actualizado con éxito.']);
    }

    // Activar o desactivar (no se elimina)
    public function estado(Vehiculo $vehiculo)
    {
        $dentro = Boleto::where('id_vehiculo', $vehiculo->id)->whereNull('hora_salida')->exists();

        if ($vehiculo->estado && $dentro) {
            return response()->json(['mensaje' => 'No se puede desactivar un vehículo que está dentro del estacionamiento.'], 422);
        }

        $vehiculo->update(['estado' => ! $vehiculo->estado]);

        $texto = $vehiculo->estado ? 'activado' : 'desactivado';

        return response()->json(['mensaje' => "Vehículo $texto con éxito."]);
    }

    private function validar(Request $request, $id = null): array
    {
        $datos = $request->validate([
            'placa' => ['required', 'string', 'max:15', Rule::unique('vehiculos', 'placa')->ignore($id)],
            'id_tipo_vehiculo' => 'required|exists:tipos_vehiculo,id',
            'id_cliente' => 'nullable|exists:clientes,id',
            'marca' => 'nullable|string|max:50',
            'modelo' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
        ]);

        $datos['placa'] = strtoupper(trim($datos['placa']));

        return $datos;
    }
}
