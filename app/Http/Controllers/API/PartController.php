<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartController extends Controller
{
    // Listar las partes del vehiculo del usuario autenticado
    public function index(Request $request)
    {
        // Validar que el usuario esta autenticado
        $vehicleId = $request->input('vehicle_id');

        // Validar que el ID del vehiculo se ha proporcionado
        if (!$vehicleId) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle ID is required'
            ], 400);
        }

        // Verificar que el vehiculo pertenece al usuario autenticado
        $vehicle = Vehicle::byUser($request->user()->id)->find($vehicleId);

        // Validar si el vehiculo existe de lo contrario retornar un error 404
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }

        // Obtener las partes del vehiculo
        // y cargar la relacion con el vehiculo
        $part = Part::with('vehicle')
            ->where('vehicle_id', $vehicleId) // Filtrar por el ID del vehiculo
            ->get(); // Obtener todas las partes del vehiculo
        return response()->json([
            'success' => true,
            'data' => $part
        ]);
    }

    // Crear una nueva parte del vehiculo
    public function store(Request $request)
    {
        // Validar los datos de entrada de forma que se cumplan las reglas de validación definidas para crear una parte del vehiculo
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'part_name' => 'required|string|max:100',
            'part_type' => 'required|string|max:50',
            'condition_status' => 'nullable|in:good,needs_service,needs_replacement',
            'last_service_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        // Validar los datos de entrada si hay errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], 422);
        }
        // Verificar que el vehiculo pertenece al usuario autenticado
        $vehicle = Vehicle::byUser($request->user()->id)->find($request->vehicle_id);

        // Verificar que el vehiculo pertenece al usuario autenticado
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found or does not belong to the authenticated user',

            ], 404);
        }
        // Crear una nueva parte del vehiculo desde el modelo Part
        $part = Part::create($request->all());

        // verificar si la parte del vehiculo se ha creado correctamente
        return response()->json([
            'success' => true,
            'message' => 'Part created successfully',
            'data' => $part->load('vehicle'),
        ], 201);
    }

    // Mostrar una parte del vehiculo especifico 
    public function show(Request $request, string $id)
    {
        // Validar el ID de la parte del vehiculo
        $part = Part::with('vehicle')->find($id);

        // Validar si la parte del vehiculo existe de lo contrario retornar un error 404 por si no existiera capturamos el error
        if (!$part) {
            return response()->json([
                'success' => false,
                'message' => 'Part not found'
            ], 404);
        }

        // Verificar que la parte del vehiculo pertenece al usuario autenticado
        if ($part->vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Verificar si la parte del vehiculo existe
        return response()->json([
            'success' => true,
            'message' => 'Part retrieved successfully',
            'data' => $part
        ]);
    }

    // Actualizar una parte del vehiculo especifico
    public function update(Request $request, string $id)
    {
        // Validar que el ID de la parte del vehiculo se ha proporcionado
        $part = Part::with('vehicle')
            ->find($id);

        // Validar si la parte del vehiculo existe de lo contrario retornar un error 404
        if (!$part || $part->vehicle->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => ' Part not found'], 404); // Retornar un error 404 si la parte del vehiculo no existe o no pertenece al usuario autenticado
        }

        // Validar los datos de entrada de forma que se cumplan las reglas de validación definidas para actualizar una parte del vehiculo
        $validator = Validator::make($request->all(), [
            'part_name' => 'required|string|max:100', // Nombre de la parte del vehiculo
            'part_type' => 'required|string|max:50', // Tipo de parte del vehiculo
            'condition_status' => 'nullable|in:good,needs_service,needs_replacement', // Estado de la parte del vehiculo
            'last_service_date' => 'nullable|date', // Fecha del ultimo servicio de la parte del vehiculo
            'notes' => 'nullable|string', // Notas adicionales sobre la parte del vehiculo
        ]);

        // Validar los datos de entrada si hay errores que cumplan con las reglas de validación definidas
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Actualizar la parte del vehiculo con los datos proporcionados
        $part->update($request->all());

        // Verificar si la parte del vehiculo se ha actualizado correctamente
        return response()->json([
            'success' => true,
            'message' => 'Part updated successfully',
            'data' => $part->load('vehicle')
        ]);
    }

    //Eliminar parte del vehiculo especifico
    public function destroy(Request $request, $id)
    {
        // Encontrar la parte del vehiculo por el Id del usuario autenticado
        $part = Part::with('vehicle')->find($id);

        // Validar si la parte del vehiculo existe de lo contrario retornar un error 404
        if (!$part || $part->vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Part not found or does not belong to the authenticated user',
            ], 404);
        }

        // Eliminar la parte del vehiculo
        $part->delete();

        // Verificar si la parte del vehiculo se ha eliminado correctamente
        return response()->json([
            'success' => true,
            'message' => 'Part deleted successfully',
            'data' => $part
        ], 200);
    }

    public function needsAttention(Request $request)
    {
        $userVehicleIds = Vehicle::byUser($request->user()->id)->pluck('id');

        $part = Part::with('vehicle')
            ->whereIn('vehicle_id', $userVehicleIds)
            ->needsAttention()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $part
        ]);
    }
    public function create() {}
    public function edit(string $id) {}
}
