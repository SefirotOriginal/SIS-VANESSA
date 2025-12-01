<?php

namespace App\Services;

class SyncConflictResolver
{
    /**
     * Resolve conflict between existing (local/server) and incoming record.
     * Both params are arrays (may come from DB/stdClass casting).
     * Policy: 'server_wins'|'local_wins'|'latest_update'|'merge'
     *
     * @param array|null $existing
     * @param array $incoming
     * @param string $policy
     * @return array resolved record
     */
    public function resolve(?array $existing, array $incoming, string $policy = 'server_wins'): array
    {
        if (empty($existing)) {
            return $incoming;
        }

        switch ($policy) {
            case 'local_wins':
                return $incoming;

            case 'latest_update':
                $incUpdated = isset($incoming['updated_at']) ? strtotime($incoming['updated_at']) : null;
                $curUpdated = isset($existing['updated_at']) ? strtotime($existing['updated_at']) : null;

                if ($incUpdated && $curUpdated) {
                    return $incUpdated >= $curUpdated ? $incoming : $existing;
                }

                // Fallback if timestamps not comparable: keep existing
                return $existing;

            case 'merge':
                // Merge shallow: non-empty incoming fields override existing fields
                $merged = $existing;
                foreach ($incoming as $k => $v) {
                    if ($v !== null && $v !== '') {
                        $merged[$k] = $v;
                    }
                }
                return $merged;

            case 'server_wins':
            default:
                return $existing;
        }
    }
}
