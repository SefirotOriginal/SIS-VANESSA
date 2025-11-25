<?php

namespace App\Listeners;

use IlluminateAuthEventsLogin;
use Illuminate\Auth\Events\Login;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
    }
}
