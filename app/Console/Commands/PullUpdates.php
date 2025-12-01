<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\PullUpdatesJob;

class PullUpdates extends Command
{
    protected $signature = 'sync:pull-updates {--now : Ejecutar de forma sincrónica en vez de en cola}';
    protected $description = 'Solicita al servidor remoto las actualizaciones y las aplica localmente';

    public function handle()
    {
        if ($this->option('now')) {
            PullUpdatesJob::dispatch()->onConnection('sync')->onQueue('default');
            $this->info('Dispatched PullUpdatesJob (sync).');
            return 0;
        }

        PullUpdatesJob::dispatch();
        $this->info('PullUpdatesJob enviado a la cola.');
        return 0;
    }
}
