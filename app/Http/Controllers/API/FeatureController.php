<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Validator;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        $vehicleId = $request->input('vehicle_id'); // Obtener el ID del vehiculo desde la solicitud

        // Validar que el ID del vehiculo se ha proporcionado
        if (!$vehicleId) {
            return response()->json([
                'success' =>  false,
                'message' => 'vehicle_id parameter ID is required'
            ], 422);
        }

        // Buscar el vehiculo por medio de ID del usuario autenticado y el ID del vehiculo
        $vehicle = Vehicle::byUser($request->user()->id)->find($vehicleId);

        // Validar que el vehiculo existe
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }


        //feactures
        // Obtener las caracteristicas del vehiculo con el ID proporcionado 
        $features = Feature::with('vehicle')
            ->where('vehicle_id', $vehicleId)
            ->get();

        // validando la respuesta de la peticion 
        return response()->json([
            'success' => true,
            'message' => 'Features retrieved successfully',
            'data' => $features
        ], 200);
    }



    public function store(Request $request)
    {
        // Validar los datos de entrada que se cumplan las reglas de validación definidas
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'feature_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        // Validar los datos de entrada si hay errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Buscar el vehiculo por medio de ID del usuario autenticado y el ID del vehiculo pero con la solicitud 
        $vehicle = Vehicle::byUser($request->user()->id)->find($request->vehicle_id);

        // Validar que el vehiculo existe
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }

        // Crear una nueva caracteristica del vehiculo
        $feature = Feature::create($request->all());

        // Retornar la respuesta de la peticion
        return response()->json([
            'success' => true,
            'message' => 'Feature created successfully',
            'data' => $feature
        ], 201);
    }

    public function show(Request $request,  $id)
    {
        $feature = Feature::with('vehicle')->find($id);

        if (!$feature) {
            return response()->json([
                'success' => false,
                'message' => 'Feature not found'
            ], 404);
        }

        if ($feature->vehicle->user()->id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => $feature
            ], 403);
        }
    }

    // Actualizar una caracteristica
    public function update(Request $request, string $id)
    {

        // Validar que el ID de la caracteristica se ha proporcionado
        $feature = Feature::with('vehicle')->find($id);

        // Validar si la caracteristica existe de lo contrario retornar un error 404
        if (!$feature || $feature->vehicle->user()->id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Feature not found',
            ], 404);
        }

        // Validar los datos de entrada que se cumplan las reglas de validación definidas
        $validator = Validator::make($request->all(), [
            'feature_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        // Validar los datos de entrada si hay errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Actualizar la caracteristica con los datos proporcionados
        $feature->update($request->all());

        // Retornar la respuesta de la peticion
        return response()->json(
            [
                'success' => true,
                'message' => 'Feature updated successfully',
                'data' => $feature
            ],
            200
        );
    }


    public function destroy(Request $request, string $id)
    {
        $feature = Feature::with('vehicle')->find($id);

        if (!$feature || $feature->vehicle->user()->id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Feature not found',
            ], 404);
        }

        $feature->delete();

        return response()->json([
            'success' => true,
            'message' => 'Feature deleted successfully',
            'data' => $feature
        ], 200);
    }

    public function toggleActive(Request $request, $id)
    {
        $feature = Feature::with('vehicle')->find($id);

        if (!$feature || $feature->vehicle->user()->id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Feature not found',
            ], 404);
        }
        $feature->update(['is_active' => !$feature->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Feature updated successfully',
            'data' => $feature
        ], 200);
    }

    public function create() {}
    public function edit(string $id) {}
}
