<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
{
    /**
     * Listar licencias de un vehículo
     */
    public function index(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');

        if (!$vehicleId) {
            return response()->json([
                'success' => false,
                'message' => 'vehicle_id parameter is required'
            ], 422);
        }

        // Verificar que el vehículo pertenece al usuario
        $vehicle = Vehicle::byUser($request->user()->id)->find($vehicleId);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }

        $licenses = License::with('vehicle')
            ->where('vehicle_id', $vehicleId)
            ->orderBy('expiry_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $licenses
        ]);
    }

    /**
     * Crear nueva licencia
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'license_type' => 'required|string|max:50',
            'license_number' => 'required|string|max:50',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'status' => 'nullable|in:valid,expired',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar que el vehículo pertenece al usuario
        $vehicle = Vehicle::byUser($request->user()->id)->find($request->vehicle_id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }

        $license = License::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'License created successfully',
            'data' => $license->load('vehicle')
        ], 201);
    }

    /**
     * Mostrar licencia específica
     */
    public function show(Request $request, $id)
    {
        $license = License::with('vehicle')->find($id);

        if (!$license) {
            return response()->json([
                'success' => false,
                'message' => 'License not found'
            ], 404);
        }

        // Verificar que el vehículo pertenece al usuario
        if ($license->vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $license
        ]);
    }

    /**
     * Actualizar licencia
     */
    public function update(Request $request, $id)
    {
        $license = License::with('vehicle')->find($id);

        if (!$license || $license->vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'License not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'license_type' => 'required|string|max:50',
            'license_number' => 'required|string|max:50',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'status' => 'nullable|in:valid,expired',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $license->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'License updated successfully',
            'data' => $license->load('vehicle')
        ]);
    }

    /**
     * Eliminar licencia
     */
    public function destroy(Request $request, $id)
    {
        $license = License::with('vehicle')->find($id);

        if (!$license || $license->vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'License not found'
            ], 404);
        }

        $license->delete();

        return response()->json([
            'success' => true,
            'message' => 'License deleted successfully'
        ]);
    }

    /**
     * Obtener licencias que vencen pronto
     */
    public function expiringSoon(Request $request)
    {
        $days = $request->input('days', 30);
        $userVehicleIds = Vehicle::byUser($request->user()->id)->pluck('id');

        $licenses = License::with('vehicle')
            ->whereIn('vehicle_id', $userVehicleIds)
            ->expiringSoon($days)
            ->orderBy('expiry_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $licenses
        ]);
    }

    /**
     * Renovar licencia (crear nueva basada en existente)
     */
    public function renew(Request $request, $id)
    {
        $license = License::with('vehicle')->find($id);

        if (!$license || $license->vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'License not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'license_type' => 'required|string|max:50',
            'license_number' => 'required|string|max:50',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Marcar la licencia anterior como expirada
        $license->update(['status' => 'expired']);
        // Verificar que el número de licencia es único
        // if (License::where('license_number', $request->license_number)
        //     ->where('vehicle_id', $license->vehicle_id)
        //     ->exists()
        // ) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'License number must be unique for the vehicle'
        //     ], 422);
        // }
        // Crear una nueva licencia con los datos proporcionados
        $newLicense = License::create([
            'vehicle_id' => $license->vehicle_id,
            'license_type' => $request->license_type,
            'license_number' => $request->license_number,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'status' => 'valid', // Nueva licencia siempre es válida al momento de crearla
        ]);

        return response()->json([
            'success' => true,
            'message' => 'License renewed successfully',
            'data' => $newLicense->load('vehicle')
        ]);
    }
}
