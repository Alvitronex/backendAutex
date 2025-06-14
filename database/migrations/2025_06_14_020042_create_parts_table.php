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
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade'); //llave foreana para el vehiculo al que pertenece la pieza
            $table->string('part_name'); // Nombre de la pieza            
            $table->string('part_type'); // Tipo de pieza (e.g., motor, frenos, etc.)
            $table->enum('condition_status', ['good', 'needs_service', 'needs_replacement'])->default('good'); // Estado de la pieza, puede ser buena, necesita servicio o necesita reemplazo, por defecto es buena
            $table->date('last_service_date')->nullable(); // Fecha del último servicio, puede ser nulo
            $table->text('notes')->nullable(); // Notas adicionales sobre la pieza, puede ser nulo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
