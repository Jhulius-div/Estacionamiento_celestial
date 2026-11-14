<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    // Pantalla con la tabla de usuarios
    public function index()
    {
        $usuarios = Usuario::with('rol')->orderBy('id')->get();
        $roles = Rol::orderBy('id')->get();

        return view('usuarios.usuarios', compact('usuarios', 'roles'));
    }

    public function tabla()
    {
        $usuarios = Usuario::with('rol')->orderBy('id')->get();

        return view('usuarios.filas', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|max:100|unique:usuarios,correo',
            'password' => 'required|string|min:6',
            'id_rol' => 'required|exists:roles,id',
        ]);

        Usuario::create($datos);

        return response()->json(['mensaje' => 'Usuario registrado con éxito.']);
    }

    public function update(Request $request, Usuario $usuario)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => ['required', 'email', 'max:100', Rule::unique('usuarios', 'correo')->ignore($usuario->id)],
            'password' => 'nullable|string|min:6',
            'id_rol' => 'required|exists:roles,id',
        ]);

        // Si no se escribe contraseña se deja la anterior
        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        $usuario->update($datos);

        return response()->json(['mensaje' => 'Usuario actualizado con éxito.']);
    }

    // Activar o desactivar (no se elimina)
    public function estado(Usuario $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return response()->json(['mensaje' => 'No puedes desactivar tu propio usuario.'], 422);
        }

        $usuario->update(['estado' => ! $usuario->estado]);

        $texto = $usuario->estado ? 'activado' : 'desactivado';

        return response()->json(['mensaje' => "Usuario $texto con éxito."]);
    }
}
