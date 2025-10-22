<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConnectedAccountsController extends Controller
{
    /**
     * Display the user's connected accounts.
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        return view('profile.connected-accounts', [
            'user' => $user,
            'connections' => [
                'google' => [
                    'connected' => !is_null($user->google_id),
                    'email' => $user->google_id ? $user->email : null,
                    'provider' => 'Google',
                    'icon' => 'google',
                ],
                'github' => [
                    'connected' => !is_null($user->github_id),
                    'email' => $user->github_id ? $user->email : null,
                    'provider' => 'GitHub',
                    'icon' => 'github',
                ],
            ],
        ]);
    }
}
