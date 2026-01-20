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

class UpdateGoogleCalendarEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $tareaId,
        public int $userId
    ) {}

    public function handle(): void
    {
        $tarea = Tarea::find($this->tareaId);
        $user  = User::find($this->userId);

        if (!$tarea || !$user) {
            \Log::warning('JOB SALE: tarea o usuario no existe', [
                'tarea_id' => $this->tareaId,
                'user_id' => $this->userId,
            ]);
            return;
        }

        if (!$user->google_refresh_token) {
            \Log::warning('JOB SALE: usuario sin google_refresh_token', [
                'user_id' => $this->userId,
            ]);
            return;
        }

        // Buscar el evento en el pivot
        $pivot = DB::table('tarea_user')
            ->where('tarea_id', $this->tareaId)
            ->where('user_id', $this->userId)
            ->first();

        if (!$pivot || !$pivot->google_event_id) {
            \Log::warning('JOB SALE: no hay google_event_id', [
                'tarea_id' => $this->tareaId,
                'user_id' => $this->userId,
                'pivot' => $pivot,
            ]);
            return;
        }

        // 🔴 VALIDACIÓN CLAVE (ESTO ES LO QUE FALTABA)
        if (!$tarea->fecha_limite) {
            \Log::warning('JOB SALE: tarea sin fecha_limite', [
                'tarea_id' => $this->tareaId,
                'user_id' => $this->userId,
            ]);
            return;
        }

        // refrescar token si expiró
        if ($user->google_token_expires_at && now()->gte($user->google_token_expires_at)) {
            $this->refreshToken($user);
        }

        // Datos actualizados del evento
        $eventData = [
            'summary' => $tarea->titulo,
            'description' => $tarea->descripcion,
            'start' => [
                'dateTime' => $tarea->fecha_limite->startOfDay()->toIso8601String(),
                'timeZone' => config('app.timezone'),
            ],
            'end' => [
                'dateTime' => $tarea->fecha_limite->endOfDay()->toIso8601String(),
                'timeZone' => config('app.timezone'),
            ],
        ];

        \Log::info('UpdateGoogleCalendarEvent ejecutado', [
            'tarea_id' => $this->tareaId,
            'user_id' => $this->userId,
            'google_event_id' => $pivot->google_event_id,
        ]);

        // PATCH al evento existente
        $response = Http::withToken($user->google_access_token)
            ->patch(
                "https://www.googleapis.com/calendar/v3/calendars/primary/events/{$pivot->google_event_id}",
                $eventData
            );

        \Log::info('PATCH GOOGLE RESPUESTA', [
            'status' => $response->status(),
            'body' => $response->body(),
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
