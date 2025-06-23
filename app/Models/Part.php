<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
use HasFactory;
    protected $fillable = [
        'vehicle_id', // ID del vehículo al que pertenece la pieza
        'part_name', // Nombre de la pieza
        'part_type', // Tipo de pieza (e.g., motor, frenos, etc.)
        'condition_status', // Estado de la pieza, puede ser buena, necesita servicio o necesita reemplazo
        'last_service_date', // Fecha del último servicio, puede ser nulo
        'notes', // Notas adicionales sobre la pieza, puede ser nulo
    ];

    protected $casts = [
        'last_service_date' => 'date', // Convierte last_service_date a tipo fecha
    ];

    // Relación con el modelo Vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class); 
    }
    // Scopes para filtrar las piezas
    public function scopeNeedsAttention($query)
    {
        return $query->whereIn('condition_status', ['needs_service', 'needs_replacement']);     
    }
    public function scopeByCondition($query, $condition) 
    { 
        return $this->orderBy('condition_status', $condition);  
    }
}
