<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EspacioController extends Controller
{
    // Pantalla con el mapa y la tabla de espacios
    public function index()
    {
        $espacios = Espacio::orderBy('piso')->orderBy('sector')->orderBy('numero')->get();

        return view('espacios.espacios', compact('espacios'));
    }

    // Filas de la tabla y mapa de celdas (se recargan con jQuery)
    public function tabla()
    {
        $espacios = Espacio::orderBy('piso')->orderBy('sector')->orderBy('numero')->get();

        return view('espacios.filas', compact('espacios'));
    }

    // Mapa de celdas libres y ocupadas
    public function mapa()
    {
        $espacios = Espacio::orderBy('piso')->orderBy('sector')->orderBy('numero')->get();

        return view('espacios.mapa', compact('espacios'));
    }

    public function store(Request $request)
    {
        Espacio::create($this->validar($request));

        return response()->json(['mensaje' => 'Espacio registrado con éxito.']);
    }

    public function update(Request $request, Espacio $espacio)
    {
        $espacio->update($this->validar($request, $espacio->id));

        return response()->json(['mensaje' => 'Espacio actualizado con éxito.']);
    }

    // Activar o desactivar (no se elimina)
    public function estado(Espacio $espacio)
    {
        if ($espacio->estado && $espacio->esta_ocupado) {
            return response()->json(['mensaje' => 'No se puede desactivar un espacio que está ocupado.'], 422);
        }

        $espacio->update(['estado' => ! $espacio->estado]);

        $texto = $espacio->estado ? 'activado' : 'desactivado';

        return response()->json(['mensaje' => "Espacio $texto con éxito."]);
    }

    private function validar(Request $request, $id = null): array
    {
        $datos = $request->validate([
            'piso' => 'required|integer|min:1|max:20',
            'sector' => 'required|string|max:10',
            'numero' => [
                'required', 'integer', 'min:1',
                Rule::unique('espacios_estacionamiento', 'numero')
                    ->where('piso', $request->piso)
                    ->where('sector', strtoupper($request->sector))
                    ->ignore($id),
            ],
        ], [
            'numero.unique' => 'Ya existe un espacio con ese piso, sector y número.',
        ]);

        $datos['sector'] = strtoupper(trim($datos['sector']));

        return $datos;
    }
}
