<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;
    protected $fillable = [
        'vehicle_id', // ID del vehículo al que pertenece la licencia
        'license_type', // Tipo de licencia (e.g., commercial, private, etc.)
        'license_number', // Número de licencia, debe ser único
        'issue_date', // Fecha de emisión de la licencia
        'expiry_date', // Fecha de vencimiento de la licencia
        'status', // Estado de la licencia (e.g., active, expired)
    ];
    protected $casts = [
        'issue_date' => 'date', // Convierte issue_date a tipo fecha
        'expiry_date' => 'date', // Convierte expiry_date a tipo fecha
    ];
    // Relación con el modelo Vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    // Scopes para filtrar las licencias
    public function scopeValid($query)
    {
        return $query->where('status', 'valid'); // Filtra licencias válidas
    }
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired'); // Filtra licencias expiradas
    }
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days)); // Filtra licencias que expiran pronto
    }

    // Mutadores/Accessors 
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date < now(); // Verifica si la licencia está expirada
    }

    public function getDaysntilExpiryAttribute()
    {
        return $this->expiry_date->diffInDays(now(), false); // Calcula los días restantes hasta el vencimiento
    }
}
