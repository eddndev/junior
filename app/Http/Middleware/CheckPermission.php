<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckPermission Middleware
 *
 * Verifies that the authenticated user has a specific permission.
 * Uses the additive permission system where permissions are accumulated from all assigned roles.
 *
 * Usage in routes:
 * Route::get('/users', [UserController::class, 'index'])->middleware('permission:gestionar-usuarios');
 *
 * Usage with multiple permissions (OR logic):
 * Route::get('/reports', [ReportController::class, 'index'])->middleware('permission:ver-reportes|generar-reportes');
 */
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions  One or more permission slugs to check (OR logic)
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            // Log unauthorized access attempt
            Log::warning('Unauthorized access attempt to protected route', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'required_permissions' => $permissions,
            ]);

            // Redirect to login if not authenticated
            return redirect()->route('login')
                ->withErrors(['email' => 'Debes iniciar sesión para acceder a esta página.']);
        }

        $user = auth()->user();

        // Check if user has at least one of the required permissions (OR logic)
        $hasPermission = false;
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            // Log permission denial for audit trail
            Log::warning('Permission denied for user', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'required_permissions' => $permissions,
                'user_permissions' => $user->getAllPermissions()->pluck('slug')->toArray(),
            ]);

            // For AJAX requests, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No tienes permisos suficientes para realizar esta acción.',
                    'required_permissions' => $permissions,
                ], 403);
            }

            // For web requests, show error page with redirect option
            abort(403, 'No tienes permisos suficientes para acceder a esta página. Permisos requeridos: ' . implode(' o ', $permissions));
        }

        return $next($request);
    }
}
