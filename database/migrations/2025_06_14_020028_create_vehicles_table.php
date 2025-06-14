<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //identificador de la tabla users para relacionar el vehiculo con un usuario
            $table->string('license_plate')->unique(); // Placa del vehiculo, debe ser unica
            $table->string('make'); // Marca del vehiculo
            $table->string('model'); // Modelo del vehiculo
            $table->integer('year'); // Año del vehiculo
            $table->string('color')->nullable(); // Color del vehiculo, puede ser nulo
            $table->enum('vehicle_type', ['sedan', 'microbus', 'trailer', 'motocicleta', 'autobus'])->default('sedan'); // Tipo de vehiculo, por defecto es coche
            $table->enum('status', ['active', 'inactive'])->default('active'); // Estado del vehiculo, puede ser activo o inactivo, por defecto es activo
            $table->date('registration_date')->nullable(); // Fecha de registro del vehiculo, puede ser nulo
            $table->timestamps(); // Creación y actualización de registros
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
