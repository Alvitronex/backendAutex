<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeUnit\FunctionUnit;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', // ID del vehículo al que pertenece el reporte
        'report_date', // Fecha del reporte
        'title', // Título del reporte
        'description', // Descripción del reporte
        'report_type', // Tipo de reporte, puede ser mantenimiento, combustible u otro
        'cost', // Costo asociado al reporte
    ];

    protected $casts = [
        'report_date' => 'date', // Convierte report_date a tipo fecha
        'cost' => 'decimal:2', // Convierte cost a tipo decimal con 2 decimales
    ];

    // Relación con el modelo Vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Scopes para filtrar los reportes
    public function scopeByType($query, $type)
    {
        return $query->where('report_type', $type);
    }
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('report_date', '>=', now()->subDays($days));
    }
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('report_date', [$startDate, $endDate]);
    }
}
