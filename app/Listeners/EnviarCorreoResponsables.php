<?php

namespace App\Listeners;

use App\Events\TareaAsignada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\TareaAsignadaMail;

class EnviarCorreoResponsables
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TareaAsignada $event)
    {
        foreach ($event->responsables as $user) {
            Mail::to($user->email)
                ->queue(new TareaAsignadaMail($event->tarea, $user));
        }
    }
}
