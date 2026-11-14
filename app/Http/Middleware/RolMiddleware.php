<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolMiddleware
{
    // Solo deja pasar a los usuarios que tengan uno de los roles indicados en la ruta
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! $request->user()->tieneRol(...$roles)) {
            abort(403, 'No tienes permiso para entrar a esta sección.');
        }

        return $next($request);
    }
}
