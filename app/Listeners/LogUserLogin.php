<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Jobs\PullUpdatesJob;

class LogUserLogin
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

    public function handle(Login $event): void
    {
        //
        $event->user->update([
            'last_login_at' => now(),
        ]);

        // Store login time in session for cash cut usage
        session(['login_time' => now()]);

        try {
            // Enviar job para traer actualizaciones del servidor (no bloquear el login)
            PullUpdatesJob::dispatch();
        } catch (\Exception $e) {
            // no bloquear si falla
        }
    }
}
