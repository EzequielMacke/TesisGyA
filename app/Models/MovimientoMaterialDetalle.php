<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoMaterialDetalle extends Model
{
    use HasFactory;

    protected $table = 'movimiento_material_detalles';

    protected $fillable = [
        'movimiento_material_id',
        'insumo_id',
        'cantidad',
        'observacion',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
    ];

    // Relación con Movimiento de Materiales
    public function movimiento()
    {
        return $this->belongsTo(MovimientoMaterial::class, 'movimiento_material_id');
    }

    // Relación con Insumo
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
}
