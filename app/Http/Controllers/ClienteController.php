<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Pantalla con la tabla de clientes
    public function index()
    {
        $clientes = Cliente::orderBy('id')->get();

        return view('clientes.clientes', compact('clientes'));
    }

    // Solo las filas de la tabla (para recargar con jQuery)
    public function tabla()
    {
        $clientes = Cliente::orderBy('id')->get();

        return view('clientes.filas', compact('clientes'));
    }

    public function store(Request $request)
    {
        Cliente::create($this->validar($request));

        return response()->json(['mensaje' => 'Cliente registrado con éxito.']);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update($this->validar($request));

        return response()->json(['mensaje' => 'Cliente actualizado con éxito.']);
    }

    // Activar o desactivar (no se elimina)
    public function estado(Cliente $cliente)
    {
        $cliente->update(['estado' => ! $cliente->estado]);

        $texto = $cliente->estado ? 'activado' : 'desactivado';

        return response()->json(['mensaje' => "Cliente $texto con éxito."]);
    }

    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:100',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $datos['es_suscriptor'] = $request->boolean('es_suscriptor');

        return $datos;
    }
}
