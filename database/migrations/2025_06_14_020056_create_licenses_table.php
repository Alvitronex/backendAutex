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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade'); // llave foránea para relacionar la licencia con un vehículo
            $table->string('license_type'); // Tipo de licencia (e.j., particular, liviana, pesada, pesada T)
            $table->string('license_number'); // Número de licencia, debe ser único
            $table->date('issue_date'); // Fecha de emisión de la licencia
            $table->date('expiry_date'); // Fecha de vencimiento de la licencia
            $table->enum('status', ['valid', 'expired'])->default('valid'); // Estado de la licencia, puede ser válida o expirada, por defecto es válida

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
