<?php

namespace Database\Seeders;

use App\Models\Part;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class PartSeeder extends Seeder
{
    public function run()
    {
        $vehicle1 = Vehicle::where('license_plate', 'ABC-123')->first();
        $vehicle2 = Vehicle::where('license_plate', 'XYZ-789')->first();
        $vehicle3 = Vehicle::where('license_plate', 'TEST-001')->first();

        // Partes para Vehículo 1 (Toyota Corolla)
        Part::create([
            'vehicle_id' => $vehicle1->id,
            'part_name' => 'Llantas Delanteras',
            'part_type' => 'Llantas',
            'condition_status' => 'good',
            'last_service_date' => '2024-01-15',
            'notes' => 'Michelin 195/65R15, buen estado',
        ]);

        Part::create([
            'vehicle_id' => $vehicle1->id,
            'part_name' => 'Motor',
            'part_type' => 'Motor',
            'condition_status' => 'good',
            'last_service_date' => '2024-05-20',
            'notes' => 'Último cambio de aceite realizado',
        ]);

        Part::create([
            'vehicle_id' => $vehicle1->id,
            'part_name' => 'Frenos Delanteros',
            'part_type' => 'Sistema de Frenos',
            'condition_status' => 'needs_service',
            'last_service_date' => '2023-08-10',
            'notes' => 'Pastillas al 30%, revisar pronto',
        ]);

        // Partes para Vehículo 2 (Honda Civic)
        Part::create([
            'vehicle_id' => $vehicle2->id,
            'part_name' => 'Filtro de Aire',
            'part_type' => 'Filtro',
            'condition_status' => 'needs_replacement',
            'last_service_date' => '2023-12-01',
            'notes' => 'Muy sucio, reemplazar urgente',
        ]);

        Part::create([
            'vehicle_id' => $vehicle2->id,
            'part_name' => 'Batería',
            'part_type' => 'Eléctrico',
            'condition_status' => 'good',
            'last_service_date' => '2024-02-14',
            'notes' => 'Batería nueva ACDelco',
        ]);

        // Partes para Vehículo 3 (Nissan Sentra)
        Part::create([
            'vehicle_id' => $vehicle3->id,
            'part_name' => 'Aceite Motor',
            'part_type' => 'Lubricantes',
            'condition_status' => 'good',
            'last_service_date' => '2024-06-01',
            'notes' => 'Sintético 5W-30, próximo cambio en 5000km',
        ]);
    }
}