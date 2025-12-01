<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ProcessSyncQueue;
use App\Console\Commands\PullUpdates;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ProcessSyncQueue::class,
        PullUpdates::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Intentar reintentos de la cola de sincronización cada minuto
        $schedule->command('sync:process-queue --limit=50')->everyMinute()->withoutOverlapping();

        // Traer actualizaciones desde el servidor cada 5 minutos
        $schedule->command('sync:pull-updates')->everyFiveMinutes()->withoutOverlapping();
    }

    protected function commands()
    {
        // no-op: laravel auto-discover
    }
}
