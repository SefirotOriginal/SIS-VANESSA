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

class PullUpdatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(SyncService $sync, \App\Services\SyncConflictResolver $resolver)
    {
        try {
            $res = $sync->pullUpdates();

            if (empty($res['ok']) || $res['ok'] === false) {
                Log::warning('PullUpdatesJob: no se obtuvieron actualizaciones: ' . json_encode($res));
                return;
            }

            $data = $res['body']['data'] ?? $res['body'] ?? [];

            foreach ($data as $item) {
                $row = (array) $item;

                // Si el servidor incluye detalles, aplicarlos; si no, hacemos upsert simple
                $reference = $row['reference_number'] ?? null;

                if ($reference) {
                    $existing = DB::table('purchases')->where('reference_number', $reference)->first();

                    $policy = config('sync.conflict_resolution', 'server_wins');

                    if ($existing) {
                        $existingArr = (array) $existing;
                        $resolved = $resolver->resolve($existingArr, $row, $policy);

                        // Si resolved es la versión entrante o policy indica aplicar incoming, actualizamos
                        if ($resolved !== $existingArr) {
                            DB::table('purchases')->where('id', $existing->id)->update(array_merge($resolved, ['updated_at' => $resolved['updated_at'] ?? now()]));
                        }

                        $localId = $existing->id;
                    } else {
                        $localId = DB::table('purchases')->insertGetId(array_merge($row, ['created_at' => $row['created_at'] ?? now(), 'updated_at' => $row['updated_at'] ?? now()]));
                    }

                    // Si el servidor trae detalles, reemplazamos los locales
                    $details = $row['details'] ?? [];
                    if (!empty($details) && is_array($details)) {
                        DB::table('purchase_details')->where('purchase_id', $localId)->delete();
                        foreach ($details as $d) {
                            $dRow = array_merge($d, [
                                'purchase_id' => $localId,
                                'created_at' => $d['created_at'] ?? now(),
                                'updated_at' => $d['updated_at'] ?? now(),
                            ]);
                            DB::table('purchase_details')->insert($dRow);
                        }
                    }
                } elseif (!empty($row['id'])) {
                    // fallback por id
                    DB::table('purchases')->updateOrInsert(
                        ['id' => $row['id']],
                        array_merge($row, [
                            'updated_at' => $row['updated_at'] ?? now(),
                            'created_at' => $row['created_at'] ?? now(),
                        ])
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('PullUpdatesJob error: ' . $e->getMessage());
        }
    }
}
