<?php

namespace Database\Seeders;

use App\Models\License;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // User::factory(10)->create();
        // llamamos a los seeders específicos para poblar las tablas de la base de datos
        $this->call([
            UserSeeder::class, // Asegúrate de que UserSeeder esté definido correctamente
            VehicleSeeder::class, // Asegúrate de que VehicleSeeder esté definido correctamente
            PartSeeder::class, // Asegúrate de que PartSeeder esté definido correctamente
            FeatureSeeder::class, // Asegúrate de que FeatureSeeder esté definido correctamente
            ReportSeeder::class, // Asegúrate de que ReportSeeder esté definido correctamente
            LicenseSeeder::class, // Asegúrate de que LicenseSeeder esté definido correctamente
            
        ]);

        
    }
}
