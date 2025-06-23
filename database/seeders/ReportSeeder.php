<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run()
    {
        $vehicle1 = Vehicle::where('license_plate', 'ABC-123')->first();
        $vehicle2 = Vehicle::where('license_plate', 'XYZ-789')->first();
        $vehicle3 = Vehicle::where('license_plate', 'TEST-001')->first();

        // Reportes para Toyota Corolla
        Report::create([
            'vehicle_id' => $vehicle1->id,
            'report_type' => 'maintenance',
            'title' => 'Cambio de Aceite',
            'description' => 'Mantenimiento rutinario del motor, cambio de aceite y filtro',
            'report_date' => '2024-05-20',
            'cost' => 65.00,
        ]);

        Report::create([
            'vehicle_id' => $vehicle1->id,
            'report_type' => 'maintenance',
            'title' => 'Revisión Técnica Anual',
            'description' => 'Inspección técnica vehicular obligatoria',
            'report_date' => '2024-03-15',
            'cost' => 35.00,
        ]);

        Report::create([
            'vehicle_id' => $vehicle1->id,
            'report_type' => 'fuel',
            'title' => 'Tanque Lleno',
            'description' => 'Llenado completo - 45 litros',
            'report_date' => '2024-06-10',
            'cost' => 52.50,
        ]);

        // Reportes para Honda Civic
        Report::create([
            'vehicle_id' => $vehicle2->id,
            'report_type' => 'maintenance',
            'title' => 'Cambio de Filtro de Aire',
            'description' => 'Reemplazo de filtro de aire del motor',
            'report_date' => '2024-04-08',
            'cost' => 25.00,
        ]);

        Report::create([
            'vehicle_id' => $vehicle2->id,
            'report_type' => 'other',
            'title' => 'Lavado y Encerado',
            'description' => 'Limpieza exterior e interior completa',
            'report_date' => '2024-06-05',
            'cost' => 20.00,
        ]);

        // Reportes para Nissan Sentra
        Report::create([
            'vehicle_id' => $vehicle3->id,
            'report_type' => 'maintenance',
            'title' => 'Servicio de 10,000 km',
            'description' => 'Mantenimiento programado: aceite, filtros, revisión general',
            'report_date' => '2024-06-01',
            'cost' => 120.00,
        ]);
    }
}