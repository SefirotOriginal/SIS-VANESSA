<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncService
{
    protected $baseUrl;
    protected $key;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = config('sync.remote_url');
        $this->key = config('sync.remote_key');
        $this->timeout = config('sync.timeout', 10);
    }

    protected function headers()
    {
        return [
            'X-SYNC-TOKEN' => $this->key,
            'Accept' => 'application/json',
        ];
    }

    public function pushPurchase(array $payload): array
    {
        $url = rtrim($this->baseUrl, '/') . '/sync/push/purchase';

        try {
            $resp = Http::timeout($this->timeout)
                ->withHeaders($this->headers())
                ->post($url, $payload);

            return [
                'ok' => $resp->ok(),
                'status' => $resp->status(),
                'body' => $resp->json(),
            ];
        } catch (\Exception $e) {
            Log::error('SyncService pushPurchase error: ' . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    public function health(): array
    {
        $url = rtrim($this->baseUrl, '/') . '/sync/health';

        try {
            $resp = Http::timeout($this->timeout)
                ->withHeaders($this->headers())
                ->get($url);

            return [
                'ok' => $resp->ok(),
                'status' => $resp->status(),
                'body' => $resp->json(),
            ];
        } catch (\Exception $e) {
            Log::error('SyncService health error: ' . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    public function pullUpdates(array $options = []): array
    {
        $url = rtrim($this->baseUrl, '/') . '/sync/pull/updates';

        try {
            $resp = Http::timeout($this->timeout)
                ->withHeaders($this->headers())
                ->post($url, $options);

            return [
                'ok' => $resp->ok(),
                'status' => $resp->status(),
                'body' => $resp->json(),
            ];
        } catch (\Exception $e) {
            Log::error('SyncService pullUpdates error: ' . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }
}
