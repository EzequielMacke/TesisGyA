<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudMaterialDetalle extends Model
{
    use HasFactory;

    protected $table = 'solicitud_material_detalles';

    protected $fillable = [
        'solicitud_material_id',
        'insumo_id',
        'cantidad_solicitada',
        'cantidad_entregada',
        'observacion',
        'terminado',
    ];

    protected $casts = [
        'cantidad_solicitada' => 'decimal:2',
        'cantidad_entregada' => 'decimal:2',
        'terminado' => 'integer',
    ];

    // Relación con SolicitudMaterial
    public function solicitudMaterial()
    {
        return $this->belongsTo(SolicitudMaterial::class, 'solicitud_material_id');
    }

    // Relación con Insumo
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
}
