<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $fillable = [
        'vehicle_id', // ID del vehículo al que pertenece la característica
        'feature_name', // Nombre de la característica
        'description', // Descripción de la característica
        'is_active', // Estado de la característica, por defecto es activa
    ];
    protected $casts = [
        'is_active' => 'boolean', // Convierte is_active a tipo booleano
    ];
    // Relación con el modelo Vehicle
    public function vehicle()   
    {
        return $this->belongsTo(Vehicle::class); 
    }
    // Scopes para filtrar las características
    public function scopeActive($query)
    {
        return $query->where('is_active', true); 
    }
}
