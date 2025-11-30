<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    protected function authorizeRequest(Request $request)
    {
        $token = $request->header('X-SYNC-TOKEN') ?? $request->input('sync_token');
        return $token && $token === config('sync.remote_key');
    }

    public function pushPurchase(Request $request)
    {
        if (!$this->authorizeRequest($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->all();

        // Aquí se debe validar y aplicar la lógica de inserción/actualización local
        // Para la primera versión simplemente guardamos en una tabla `purchases` si no existe

        // El payload esperado: purchase + details
        try {
            DB::beginTransaction();

            // Ejemplo simple: si viene `reference_number`, buscar y crear si no existe
            $ref = $data['purchase']['reference_number'] ?? null;

            if ($ref) {
                $exists = DB::table('purchases')->where('reference_number', $ref)->first();
                if (!$exists) {
                    DB::table('purchases')->insert(array_merge($data['purchase'], ['created_at' => now(), 'updated_at' => now()]));
                }
            }

            DB::commit();
            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
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
