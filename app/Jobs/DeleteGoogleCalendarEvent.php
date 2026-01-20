<?php

namespace App\Jobs;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DeleteGoogleCalendarEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $tareaId,
        public int $userId
    ) {}

    public function handle(): void
    {
        $user = User::find($this->userId);
        if (!$user || !$user->google_refresh_token) {
            return;
        }

        // Buscar el google_event_id en la tabla pivote
        $pivot = DB::table('tarea_user')
            ->where('tarea_id', $this->tareaId)
            ->where('user_id', $this->userId)
            ->first();

        if (!$pivot || !$pivot->google_event_id) {
            return;
        }

        // Refrescar token si expiró
        if ($user->google_token_expires_at && now()->gte($user->google_token_expires_at)) {
            $this->refreshToken($user);
        }

        // Borrar evento en Google Calendar
        Http::withToken($user->google_access_token)
            ->delete(
                "https://www.googleapis.com/calendar/v3/calendars/primary/events/{$pivot->google_event_id}"
            );

        // Limpiar el google_event_id del pivot
        DB::table('tarea_user')
            ->where('tarea_id', $this->tareaId)
            ->where('user_id', $this->userId)
            ->update([
                'google_event_id' => null,
            ]);
    }

    protected function refreshToken(User $user): void
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'refresh_token' => $user->google_refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        $data = $response->json();

        $user->update([
            'google_access_token' => $data['access_token'],
            'google_token_expires_at' => now()->addSeconds($data['expires_in']),
        ]);
    }
}