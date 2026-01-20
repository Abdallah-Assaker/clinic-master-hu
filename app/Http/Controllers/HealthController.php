<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    /**
     * Basic health check endpoint
     */
    public function check(): JsonResponse
    {
        return response()->json([
            'status' => 'OK',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Detailed health check with service status
     */
    public function detailed(): JsonResponse
    {
        $health = [
            'status' => 'OK',
            'timestamp' => now()->toIso8601String(),
            'services' => []
        ];

        // Check database
        try {
            DB::connection()->getPdo();
            $health['services']['database'] = [
                'status' => 'OK',
                'connection' => config('database.default'),
            ];
        } catch (\Exception $e) {
            $health['services']['database'] = [
                'status' => 'ERROR',
                'message' => 'Database connection failed',
            ];
            $health['status'] = 'DEGRADED';
        }

        // Check cache
        try {
            Cache::put('health_check', true, 10);
            $cacheWorks = Cache::get('health_check') === true;
            Cache::forget('health_check');
            
            $health['services']['cache'] = [
                'status' => $cacheWorks ? 'OK' : 'ERROR',
                'driver' => config('cache.default'),
            ];
        } catch (\Exception $e) {
            $health['services']['cache'] = [
                'status' => 'ERROR',
                'message' => 'Cache connection failed',
            ];
            $health['status'] = 'DEGRADED';
        }

        // Check storage
        $storagePath = storage_path('logs');
        $health['services']['storage'] = [
            'status' => is_writable($storagePath) ? 'OK' : 'ERROR',
            'writable' => is_writable($storagePath),
        ];

        $statusCode = $health['status'] === 'OK' ? 200 : 503;
        
        return response()->json($health, $statusCode);
    }
}
