<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Listar reportes de un vehículo
     */
    public function index(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        $type = $request->input('type');
        $limit = $request->input('limit', 50);

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

        $query = Report::with('vehicle')
            ->where('vehicle_id', $vehicleId)
            ->orderBy('report_date', 'desc');

        if ($type) {
            $query->byType($type);
        }

        $reports = $query->limit($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    /**
     * Crear nuevo reporte
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'report_type' => 'required|in:maintenance,inspection,fuel,other',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'report_date' => 'required|date',
            'cost' => 'nullable|numeric|min:0',
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

        $report = Report::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Report created successfully',
            'data' => $report->load('vehicle')
        ], 201);
    }

    /**
     * Mostrar reporte específico
     */
    public function show(Request $request, $id)
    {
        $report = Report::with('vehicle')->find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], 404);
        }

        // Verificar que el vehículo pertenece al usuario
        if ($report->vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Actualizar reporte
     */
    public function update(Request $request, $id)
    {
        $report = Report::with('vehicle')->find($id);

        if (!$report || $report->vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:maintenance,inspection,fuel,other',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'report_date' => 'required|date',
            'cost' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $report->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Report updated successfully',
            'data' => $report->load('vehicle')
        ]);
    }

    /**
     * Eliminar reporte
     */
    public function destroy(Request $request, $id)
    {
        $report = Report::with('vehicle')->find($id);

        if (!$report || $report->vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], 404);
        }

        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Report deleted successfully'
        ]);
    }

    /**
     * Obtener estadísticas de reportes
     */
    public function stats(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        $year = $request->input('year', date('Y'));

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

        $reports = Report::where('vehicle_id', $vehicleId)
            ->whereYear('report_date', $year)
            ->get();

        $stats = [
            'total_reports' => $reports->count(),
            'total_cost' => $reports->sum('cost'),
            'by_type' => [
                'maintenance' => $reports->where('report_type', 'maintenance')->count(),
                'inspection' => $reports->where('report_type', 'inspection')->count(),
                'fuel' => $reports->where('report_type', 'fuel')->count(),
                'other' => $reports->where('report_type', 'other')->count(),
            ],
            'monthly_costs' => $reports->groupBy(function ($report) {
                return $report->report_date->format('Y-m');
            })->map(function ($monthReports) {
                return $monthReports->sum('cost');
            }),
            'recent_reports' => $reports->sortByDesc('report_date')->take(5)->values(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
