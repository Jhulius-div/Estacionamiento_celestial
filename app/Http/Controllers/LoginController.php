<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Pantalla de login
    public function mostrar()
    {
        return view('login');
    }

    // Iniciar sesión (solo usuarios activos)
    public function entrar(Request $request)
    {
        $datos = $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        $credenciales = [
            'correo' => $datos['correo'],
            'password' => $datos['password'],
            'estado' => 1,
        ];

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors(['correo' => 'Correo o contraseña incorrectos, o el usuario está desactivado.'])
            ->onlyInput('correo');
    }

    // Cerrar sesión
    public function salir(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
