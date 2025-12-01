<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\SyncService;

class ProcessSyncQueue extends Command
{
    protected $signature = 'sync:process-queue {--limit=100}';
    protected $description = 'Procesa la tabla sync_queue intentando reenviar items pendientes al servidor remoto';

    public function handle(SyncService $sync)
    {
        $limit = (int) $this->option('limit');

        $items = DB::table('sync_queue')->orderBy('created_at')->limit($limit)->get();

        if ($items->isEmpty()) {
            $this->info('No hay items en sync_queue.');
            return 0;
        }

        foreach ($items as $item) {
            $this->line("Procesando sync_queue id={$item->id} type={$item->item_type}");

            $payload = json_decode($item->payload, true) ?: [];

            try {
                $res = null;

                if ($item->item_type === 'purchase') {
                    $res = $sync->pushPurchase($payload);
                } else {
                    // Future: manejar otros tipos
                    $res = ['ok' => false, 'error' => 'unknown item_type'];
                }

                if (!empty($res['ok'])) {
                    DB::table('sync_queue')->where('id', $item->id)->delete();
                    $this->info("Item {$item->id} sincronizado y eliminado.");
                } else {
                    $attempts = max(1, (int) $item->attempts) + 1;
                    DB::table('sync_queue')->where('id', $item->id)->update([
                        'attempts' => $attempts,
                        'last_error' => $res['error'] ?? json_encode($res['body'] ?? 'unknown'),
                        'updated_at' => now(),
                    ]);
                    $this->warn("Item {$item->id} fallo en intento {$attempts}.");
                }
            } catch (\Exception $e) {
                Log::error('ProcessSyncQueue error: ' . $e->getMessage());
                DB::table('sync_queue')->where('id', $item->id)->update([
                    'attempts' => max(1, (int) $item->attempts) + 1,
                    'last_error' => $e->getMessage(),
                    'updated_at' => now(),
                ]);
            }
        }

        return 0;
    }
}
