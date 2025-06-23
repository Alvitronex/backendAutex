<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run()
    {
        $user1 = User::where('username', 'admin')->first();
        $user2 = User::where('username', 'user')->first();

        // Check if users exist before creating vehicles
        if (!$user1 || !$user2) {
            throw new \Exception('Required users not found. Make sure UserSeeder runs before VehicleSeeder.');
        }

        Vehicle::create([
            'user_id' => $user1->id,
            'license_plate' => 'ABC-123',
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2020,
            'color' => 'Blanco',
            'vehicle_type' => 'sedan',
            'status' => 'active',
            'registration_date' => '2020-01-15',
        ]);

        Vehicle::create([
            'user_id' => $user1->id,
            'license_plate' => 'XYZ-789',
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2019,
            'color' => 'Negro',
            'vehicle_type' => 'sedan',
            'status' => 'active',
            'registration_date' => '2019-06-20',
        ]);

        Vehicle::create([
            'user_id' => $user2->id,
            'license_plate' => 'TEST-001',
            'make' => 'Nissan',
            'model' => 'Sentra',
            'year' => 2021,
            'color' => 'Azul',
            'vehicle_type' => 'sedan',
            'status' => 'active',
            'registration_date' => '2021-03-10',
        ]);
    }
}
