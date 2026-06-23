<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsumoUtilizadoDetalle extends Model
{
    protected $table = 'insumo_utilizado_detalle';

    protected $fillable = [
        'insumo_utilizado_id',
        'insumo_id',
        'cantidad',
    ];

    // Relación con InsumoUtilizado
    public function insumoUtilizado()
    {
        return $this->belongsTo(InsumoUtilizado::class, 'insumo_utilizado_id');
    }

    // Relación con Insumo
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
}
