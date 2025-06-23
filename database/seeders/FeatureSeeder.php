<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run()
    {
        $vehicle1 = Vehicle::where('license_plate', 'ABC-123')->first();
        $vehicle2 = Vehicle::where('license_plate', 'XYZ-789')->first();
        $vehicle3 = Vehicle::where('license_plate', 'TEST-001')->first();

        // Features para Toyota Corolla
        Feature::create([
            'vehicle_id' => $vehicle1->id,
            'feature_name' => 'Aire Acondicionado',
            'description' => 'Sistema de climatización automático',
            'is_active' => true,
        ]);

        Feature::create([
            'vehicle_id' => $vehicle1->id,
            'feature_name' => 'GPS Integrado',
            'description' => 'Sistema de navegación con pantalla táctil',
            'is_active' => true,
        ]);

        Feature::create([
            'vehicle_id' => $vehicle1->id,
            'feature_name' => 'Cámara Trasera',
            'description' => 'Cámara de reversa con líneas guía',
            'is_active' => true,
        ]);

        // Features para Honda Civic
        Feature::create([
            'vehicle_id' => $vehicle2->id,
            'feature_name' => 'Bluetooth',
            'description' => 'Conectividad inalámbrica para audio',
            'is_active' => true,
        ]);

        Feature::create([
            'vehicle_id' => $vehicle2->id,
            'feature_name' => 'Control Crucero',
            'description' => 'Control de velocidad automático',
            'is_active' => false,
        ]);

        // Features para Nissan Sentra
        Feature::create([
            'vehicle_id' => $vehicle3->id,
            'feature_name' => 'Sensores de Parqueo',
            'description' => 'Sensores ultrasónicos traseros',
            'is_active' => true,
        ]);

        Feature::create([
            'vehicle_id' => $vehicle3->id,
            'feature_name' => 'Android Auto',
            'description' => 'Integración con smartphone Android',
            'is_active' => true,
        ]);
    }
}