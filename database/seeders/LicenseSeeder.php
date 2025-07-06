<?php

namespace Database\Seeders;

use App\Models\License;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class LicenseSeeder extends Seeder
{
    public function run()
    {
        // Asegurarse de que los vehículos existen antes de crear las licencias
        $vehicle1 = Vehicle::where('license_plate', 'ABC-123')->first(); 
        $vehicle2 = Vehicle::where('license_plate', 'XYZ-789')->first();
        $vehicle3 = Vehicle::where('license_plate', 'TEST-001')->first();


        // Licencias para Toyota Corolla
        License::create([
            'vehicle_id' => $vehicle1->id,
            'license_type' => 'Registro Vehicular',
            'license_number' => 'REG-2024-001',
            'issue_date' => '2024-01-01',
            'expiry_date' => '2025-01-01',
            'status' => 'valid',
        ]);

        License::create([
            'vehicle_id' => $vehicle1->id,
            'license_type' => 'Seguro Obligatorio',
            'license_number' => 'SEG-2024-ABC123',
            'issue_date' => '2024-01-15',
            'expiry_date' => '2025-01-15',
            'status' => 'valid',
        ]);

        // Licencias para Honda Civic
        License::create([
            'vehicle_id' => $vehicle2->id,
            'license_type' => 'Registro Vehicular',
            'license_number' => 'REG-2024-002',
            'issue_date' => '2024-01-01',
            'expiry_date' => '2025-01-01',
            'status' => 'valid',
        ]);

        License::create([
            'vehicle_id' => $vehicle2->id,
            'license_type' => 'Seguro Obligatorio',
            'license_number' => 'SEG-2024-XYZ789',
            'issue_date' => '2024-02-01',
            'expiry_date' => '2024-12-15', // Esta vence pronto
            'status' => 'valid',
        ]);

        // Licencias para Nissan Sentra
        License::create([
            'vehicle_id' => $vehicle3->id,
            'license_type' => 'Registro Vehicular',
            'license_number' => 'REG-2024-003',
            'issue_date' => '2024-03-10',
            'expiry_date' => '2025-03-10',
            'status' => 'valid',
        ]);

        License::create([
            'vehicle_id' => $vehicle3->id,
            'license_type' => 'Revisión Técnica',
            'license_number' => 'RT-2023-TEST001',
            'issue_date' => '2023-08-15',
            'expiry_date' => '2024-08-15', // Ya venció
            'status' => 'expired',
        ]);
    }
}
