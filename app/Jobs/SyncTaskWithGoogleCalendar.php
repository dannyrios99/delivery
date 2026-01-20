<?php

namespace App\Jobs;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SyncTaskWithGoogleCalendar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $tareaId,
        public int $userId
    ) {}

    public function handle()
    {
        $tarea = Tarea::find($this->tareaId);
        $user  = User::find($this->userId);

        if (!$tarea || !$user) {
            return;
        }

        if (!$user->google_refresh_token) {
            return;
        }

        // refrescar token si expiró
        if ($user->google_token_expires_at && now()->gte($user->google_token_expires_at)) {
            $this->refreshToken($user);
        }

        // datos del evento
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

        $response = Http::withToken($user->google_access_token)
            ->post('https://www.googleapis.com/calendar/v3/calendars/primary/events', $eventData);

        if (!$response->successful()) {
            return;
        }

        $googleEventId = $response->json('id');

        // 🔑 GUARDAR google_event_id EN tarea_user
        $tarea->responsables()->updateExistingPivot(
            $user->id,
            [
                'google_event_id' => $googleEventId,
            ]
        );
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
