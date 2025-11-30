<?php

namespace App\Jobs;

use App\Services\SyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PushPurchaseToServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $purchase;

    public function __construct($purchase)
    {
        $this->purchase = $purchase;
    }

    public function handle(SyncService $sync)
    {
        $payload = [
            'purchase' => $this->purchase->toArray(),
        ];

        $res = $sync->pushPurchase($payload);

        if (empty($res['ok']) || $res['ok'] === false) {
            // Guardar en tabla local de reintentos para sincronización posterior
            try {
                DB::table('sync_queue')->insert([
                    'item_type' => 'purchase',
                    'item_id' => $this->purchase->id,
                    'payload' => json_encode($payload),
                    'attempts' => 1,
                    'last_error' => $res['error'] ?? json_encode($res['body'] ?? 'unknown'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error('PushPurchaseToServer: failed to persist sync_queue: ' . $e->getMessage());
            }
        }
    }
}
