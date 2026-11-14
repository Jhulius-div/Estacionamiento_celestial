<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    // Pantalla para cambiar el nombre y el logo
    public function index()
    {
        $configuracion = Configuracion::first();

        return view('configuracion.configuracion', compact('configuracion'));
    }

    // Guardar el nombre y el logo
    public function guardar(Request $request)
    {
        $request->validate([
            'nombre_estacionamiento' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $configuracion = Configuracion::first();
        $configuracion->nombre_estacionamiento = $request->nombre_estacionamiento;

        // Quitar el logo actual
        if ($request->boolean('quitar_logo')) {
            $this->borrarLogo($configuracion);
            $configuracion->logo = null;
        }

        // Subir un logo nuevo
        if ($request->hasFile('logo')) {
            $archivo = $request->file('logo');
            $nombre = 'logo_' . time() . '.' . $archivo->extension();

            $archivo->move(public_path('uploads'), $nombre);

            $this->borrarLogo($configuracion);
            $configuracion->logo = $nombre;
        }

        $configuracion->save();

        return response()->json(['mensaje' => 'Configuración guardada con éxito.']);
    }

    // Borra el archivo del logo anterior
    private function borrarLogo($configuracion)
    {
        if ($configuracion->logo && file_exists(public_path('uploads/' . $configuracion->logo))) {
            unlink(public_path('uploads/' . $configuracion->logo));
        }
    }
}
