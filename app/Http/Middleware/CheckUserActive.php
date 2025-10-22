<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckUserActive Middleware
 *
 * Ensures that the authenticated user has an active status (is_active = true).
 * If the user is inactive, they are logged out and redirected to login with an error message.
 *
 * Usage:
 * - Apply to routes that require active users
 * - Typically applied globally or to dashboard/protected routes
 */
class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Check if user is active
            if (!$user->is_active) {
                // Log the user out
                Auth::logout();

                // Invalidate the session
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirect to login with error message
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Tu cuenta ha sido desactivada. Por favor, contacta al administrador para más información.',
                    ])
                    ->withInput($request->only('email'));
            }

            // Check if user is soft-deleted (extra security)
            if ($user->trashed()) {
                // Log the user out
                Auth::logout();

                // Invalidate the session
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirect to login with error message
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Tu cuenta no está disponible. Por favor, contacta al administrador.',
                    ])
                    ->withInput($request->only('email'));
            }
        }

        return $next($request);
    }
}
