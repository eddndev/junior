<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            abort(403, 'No autorizado. Debe iniciar sesión.');
        }

        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole($role)) {
            abort(403, 'No tiene el rol necesario para acceder a este recurso.');
        }

        return $next($request);
    }
}
