<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class GoogleCalendarController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes([
                'https://www.googleapis.com/auth/calendar.events'
            ])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent'
            ])
            ->stateless()
            ->redirect();
    }

        public function callback()
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        // 🔑 Re-autenticamos el usuario
        $user = Auth::user();

        if (!$user) {
            // fallback: redirige con error claro
            return redirect('/login')->with('error', 'Debes iniciar sesión primero');
        }

        $user->update([
            'google_access_token' => $googleUser->token,
            'google_refresh_token' => $googleUser->refreshToken,
            'google_token_expires_at' => now()->addSeconds($googleUser->expiresIn),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Google Calendar conectado correctamente');

    }
}

