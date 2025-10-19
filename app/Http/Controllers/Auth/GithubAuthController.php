<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GithubAuthController extends Controller
{
    /**
     * Redirige al usuario a la página de autenticación de GitHub.
     */
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtiene la información del usuario de GitHub y maneja la asociación de cuenta.
     * Solo permite asociar cuentas OAuth con usuarios existentes registrados por RRHH.
     */
    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            // Busca si el usuario ya existe por el ID de GitHub
            $user = User::where('github_id', $githubUser->getId())->first();

            if ($user) {
                // Si existe, actualiza el token
                $user->update([
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken,
                ]);
            } else {
                // Si no existe por ID de GitHub, busca por email
                $user = User::where('email', $githubUser->getEmail())->first();

                if ($user) {
                    // Si existe por email, vincula la cuenta con el ID de GitHub
                    // y verifica el correo electrónico automáticamente
                    $user->update([
                        'github_id' => $githubUser->getId(),
                        'github_token' => $githubUser->token,
                        'github_refresh_token' => $githubUser->refreshToken,
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);
                } else {
                    // Si no existe, rechaza el acceso
                    return redirect('/login')->with('error', 'Este correo electrónico no está autorizado para acceder al sistema. Por favor, contacta al departamento de RRHH.');
                }
            }

            // Inicia sesión con el usuario encontrado
            Auth::login($user, remember: true);

            // Redirige al usuario a su dashboard
            return redirect()->intended(route('dashboard', absolute: false));

        } catch (Exception $e) {
            // Si algo sale mal (ej. el usuario deniega el acceso),
            // se registra el error y se redirige al login con un mensaje.
            Log::error('Error en la autenticación con GitHub', ['exception' => $e]);

            return redirect('/login')->with('error', 'No se pudo autenticar con GitHub. Por favor, inténtelo de nuevo.');
        }
    }
}
