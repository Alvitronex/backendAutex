<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    // Listar los vehiculos de los usuarios autenticados
    public function index(Request $request)
    {
        $vehicles = Vehicle::with(['parts', 'features', 'reports', 'licenses'])
            ->byUser($request->user()->id)
            ->active()
            ->get();
        return response()->json([
            'success' => true,
            'data' => $vehicles
        ]);
    }
    // Crear nuevo vehiculo 
    public function store(Request $request)
    {
        // Validar los datos de entrada de forma
        // que se cumplan las reglas de validación definidas
        $validator = Validator::make($request->all(), [
            'license_plate' => 'required|string |max:20|unique:vehicles',
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'vehicle_type' => 'required|string|max:30',
            //'status' => 'required|string|in:active,inactive',
            'color' => 'required|string|max:30',
            'registration_date' => 'required|date',
        ]);

        // Validar los datos de entrada si hay errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        // Crear un nuevo vehiculo desde el modelo Vehicle
        $vehicle = Vehicle::create([
            'user_id' => $request->user()->id,
            'license_plate' => $request->license_plate,
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'vehicle_type' => $request->vehicle_type,
            'color' => $request->color,
            //'status' => $request->status ?? 'active',
            'registration_date' => $request->registration_date,
        ]);
        // Verificar si el vehiculo se ha creado correctamente
        return response()->json([
            'success' => true,
            'message' => 'Vehicle created successfully',
            'data' => $vehicle->load(['parts', 'features', 'reports', 'licenses'])
        ], 201);
    }
    // Mostrar un vehiculo especifico
    public function show(Request $request, $id)
    {
        // Validar el ID del vehiculo
        $vehicle = Vehicle::with(['parts', 'features', 'reports', 'licenses'])
            ->byUser($request->user()->id)
            ->active()
            ->findOrFail($id);

        // Validar si el vehiculo existe
        if (!$vehicle) {
            return response()->json([
                'success' => true,
                'data' => $vehicle
            ]);
        }
    }
    // Actualizar un vehiculo especifico
    public function update(Request $request, string $id)
    {
        // Validar los datos de entrada de forma que se cumplan las reglas de validación definidas
        $vehicle = Vehicle::byUser($request->user()->id)->find($id);

        // Validar los datos de entrada si hay errores
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }
        // Validar los datos de entrada de forma que se cumplan las reglas de validación definidas
        $validator = Validator::make($request->all(), [
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $vehicle->id,
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'vehicle_type' => 'required|string|max:30',
            'status' => 'required|string|in:active,inactive',
            'color' => 'required|string|max:30',
            'registration_date' => 'required|date',
        ]);

        // Validar los datos de entrada si hay errores 
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        // Actualizar el vehiculo con los datos proporcionados
        $vehicle->update($request->all());

        // Verificar si el vehiculo se ha actualizado correctamente
        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully',
            'data' => $vehicle->load(['parts', 'features', 'reports', 'licenses'])
        ], 200);
    }
    // Eliminar y desactivar un vehiculo especifico
    public function destroy(Request $request, $id)
    {
        $vehicle = Vehicle::byUser(($request->user()->id)->find($id));

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found',

            ], 404);
        }
        // Desactivar el vehiculo
        // $vehicle->status = 'inactive';
        // $vehicle->save();

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Vehicle deactivated successfully',
        //     'data' => $vehicle->load(['parts', 'features', 'reports', 'licenses'])
        // ], 200);

        // Eliminar el vehiculo
        $vehicle->delete();
        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully',
        ], 200);
    }
    // Dashboard de vehiculos
    // Muestra un resumen de los vehiculos del usuario
    // incluyendo partes que necesitan atencion, licencias proximas a vencer y reportes recientes
    // Este metodo se puede utilizar para mostrar un resumen de los vehiculos del usuario           

    public function dashboard(Request $request, $id)
    {
        // Validar el ID del vehiculo
        $vehicle = Vehicle::with([
            // Cargar partes que necesitan atencion
            'parts' => function ($query) {
                $query->needsAttention();
            },
            // Cargar proximas por vencerse
            'licenses' => function ($query) {
                $query->expiringSoon();
            },
            // Cargar reportes recientes 
            'reports' => function ($query) {
                $query->recent(30)->orderBy('created_at', 'desc');
            },
        ])->byUser($request->user()->id)->find($id); // Buscar el vehiculo por ID 

        // Validar si el vehiculo existe
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found',
            ], 404);
        }

        // Retornar el dashboard del vehiculo
        return response()->json([
            'success' => true,
            'data' => [
                'vehicle' => $vehicle,
                'alerts' => [
                    'parts_needing_attention' => $vehicle->parts->count(),
                    'licenses_expiring_soon' => $vehicle->licenses->count(),
                    'recent_reports' => $vehicle->reports->count(),
                ],
                'stats' => [
                    'total_parts' => $vehicle->parts()->count(),
                    'total_features' => $vehicle->features()->count(),
                    'total_reports' => $vehicle->reports()->count(),
                    'total_licenses' => $vehicle->licenses()->count(),
                ]
            ]
        ], 200);
    }
    public function edit(string $id) {}
    public function create() {}
}
