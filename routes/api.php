<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\VehicleController;
use App\Http\Controllers\API\PartController;
use App\Http\Controllers\API\FeatureController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\LicenseController;

/*
|--------------------------------------------------------------------------
| API Routes - Autex v1.0
|--------------------------------------------------------------------------
*/

// Rutas públicas (sin autenticación)
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Ruta de prueba pública
Route::get('/test', function () {
    return response()->json([
        'message' => 'Autex API is working!',
        'version' => '1.0.0',
        'timestamp' => now(),
        'endpoints' => [
            'auth' => '/api/auth/*',
            'vehicles' => '/api/vehicles',
            'parts' => '/api/parts',
            'features' => '/api/features',
            'reports' => '/api/reports',
            'licenses' => '/api/licenses',
        ]
    ]);
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('users')->group(function () {
        Route::get('/', [AuthController::class, 'getAllUsers']);
        Route::get('/paginated', [AuthController::class, 'getUsersPaginated']);
        Route::get('/search', [AuthController::class, 'searchUsers']);
    });
    // ==========================================
    // AUTENTICACIÓN
    // ==========================================
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // ==========================================
    // VEHÍCULOS
    // ==========================================
    Route::apiResource('vehicles', VehicleController::class);
    Route::get('vehicles/{id}/dashboard', [VehicleController::class, 'dashboard']);

    // ==========================================
    // PARTES
    // ==========================================
    Route::apiResource('parts', PartController::class);
    Route::get('parts-needs-attention', [PartController::class, 'needsAttention']);

    // ==========================================
    // CARACTERÍSTICAS
    // ==========================================
    Route::apiResource('features', FeatureController::class);
    Route::patch('features/{id}/toggle', [FeatureController::class, 'toggleActive']);

    // ==========================================
    // REPORTES
    // ==========================================
    Route::apiResource('reports', ReportController::class);
    Route::get('reports-stats', [ReportController::class, 'stats']);

    // ==========================================
    // LICENCIAS
    // ==========================================
    Route::apiResource('licenses', LicenseController::class);
    Route::get('licenses-expiring', [LicenseController::class, 'expiringSoon']);
    Route::post('licenses/{id}/renew', [LicenseController::class, 'renew']);

    // ==========================================
    // DASHBOARD GENERAL
    // ==========================================
    Route::get('dashboard', function (Request $request) {
        $user = $request->user();
        $vehicles = $user->vehicles()->with(['parts', 'licenses'])->get();

        $totalVehicles = $vehicles->count();
        $partsNeedingAttention = $vehicles->flatMap->parts->where('condition_status', '!=', 'good')->count();
        $licensesExpiringSoon = $vehicles->flatMap->licenses->filter(function ($license) {
            return $license->expiry_date <= now()->addDays(30) && $license->status === 'valid';
        })->count();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'summary' => [
                    'total_vehicles' => $totalVehicles,
                    'parts_needing_attention' => $partsNeedingAttention,
                    'licenses_expiring_soon' => $licensesExpiringSoon,
                ],
                'vehicles' => $vehicles,
            ]
        ]);
    });
});
