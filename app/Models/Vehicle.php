<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // ID del usuario propietario
        'license_plate', // Placa del vehículo
        'make', // Marca del vehículo
        'model', // Marca del vehículo
        'year', // Año de fabricación
        'color', // Color del vehículo
        'vehicle_type', // Tipo de vehículo (e.g., coche, moto, camión)
        'status', // Estado del vehículo (e.g., activo, inactivo, en reparación)
        'registration_date', // Fecha de registro del vehículo
    ];

    protected $casts = [
        'registration_date' => 'date', // Convertir a tipo fecha
        'year' => 'integer', // Convertir a tipo entero
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class); // Relación con el modelo User
    }

    public function parts()
    {
        return $this->hasMany(Part::class); // Relación con el modelo Part
    }

    public function features()
    {
        return $this->hasMany(Feature::class); // Relación con el modelo Feature
    }

    public function reports()
    {
        return $this->hasMany(Report::class); // Relación con el modelo Report
    }

    public function licenses()
    {
        return $this->hasMany(License::class); // Relación con el modelo License
    }

    // Scopes útiles
    public function scopeActive($query)
    {
        return $query->where('status', 'active'); // Filtrar vehículos activos
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId); // Filtrar vehículos por ID de usuario
    }
}
