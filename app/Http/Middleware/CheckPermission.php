<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            abort(403, 'No autorizado. Debe iniciar sesión.');
        }

        // Verificar que el usuario tenga el permiso requerido
        if (!auth()->user()->hasPermission($permission)) {
            abort(403, 'No tiene permisos suficientes para realizar esta acción.');
        }

        return $next($request);
    }
}
