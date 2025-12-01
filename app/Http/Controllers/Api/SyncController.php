<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\SyncConflictResolver;

class SyncController extends Controller
{
    protected function authorizeRequest(Request $request)
    {
        $token = $request->header('X-SYNC-TOKEN') ?? $request->input('sync_token');
        return $token && $token === config('sync.remote_key');
    }

    public function pushPurchase(Request $request, SyncConflictResolver $resolver)
    {
        if (!$this->authorizeRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $data = $request->all();

        // Payload esperado: ['purchase' => [...], 'details' => [...]]
        try {
            DB::beginTransaction();

            $purchaseData = $data['purchase'] ?? [];
            $details = $data['details'] ?? [];

            $ref = $purchaseData['reference_number'] ?? null;

            if (!$ref) {
                DB::rollBack();
                return response()->json(['ok' => false, 'error' => 'missing reference_number'], 400);
            }

            $existing = DB::table('purchases')->where('reference_number', $ref)->first();

            $policy = config('sync.conflict_resolution', 'server_wins');

            if ($existing) {
                $existingArr = (array) $existing;
                $resolved = $resolver->resolve($existingArr, $purchaseData, $policy);

                // Si resolver devuelve la versión entrante, aplicamos; si devuelve existing, no hacemos nada
                if ($resolved === $purchaseData || $policy === 'local_wins' || ($policy === 'latest_update' && (isset($purchaseData['updated_at']) && strtotime($purchaseData['updated_at']) >= strtotime($existing->updated_at)))) {
                    DB::table('purchases')->where('id', $existing->id)->update(array_merge($resolved, ['updated_at' => $resolved['updated_at'] ?? now()]));
                }

                $purchaseId = $existing->id;
            } else {
                $purchaseId = DB::table('purchases')->insertGetId(array_merge($purchaseData, ['created_at' => $purchaseData['created_at'] ?? now(), 'updated_at' => $purchaseData['updated_at'] ?? now()]));
            }

            // Reemplazamos detalles de la compra con los que vienen en el payload
            if (!empty($details) && is_array($details)) {
                // Eliminamos detalles antiguos vinculados a esta compra
                DB::table('purchase_details')->where('purchase_id', $purchaseId)->delete();

                foreach ($details as $d) {
                    $dRow = array_merge($d, [
                        'purchase_id' => $purchaseId,
                        'created_at' => $d['created_at'] ?? now(),
                        'updated_at' => $d['updated_at'] ?? now(),
                    ]);

                    // Evitar claves que no existan en la tabla
                    DB::table('purchase_details')->insert($dRow);
                }
            }

            DB::commit();
            return response()->json(['ok' => true, 'purchase_id' => $purchaseId]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function health(Request $request)
    {
        if (!$this->authorizeRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['ok' => true, 'time' => now()->toDateTimeString()]);
    }

    public function pullUpdates(Request $request)
    {
        if (!$this->authorizeRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Aquí puede devolverse un conjunto de datos que el cliente local necesita aplicar
        // Por simplicidad devolvemos las últimas compras desde el servidor
        $since = $request->input('since');

        $query = DB::table('purchases')->orderBy('id', 'desc');
        if ($since) {
            $query->where('updated_at', '>', $since);
        }

        $items = $query->limit(200)->get();

        return response()->json(['ok' => true, 'data' => $items]);
    }
}
