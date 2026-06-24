<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioRealizadoInsumo extends Model
{
    protected $table = 'servicio_realizado_insumo';

    protected $fillable = [
        'servicio_realizado_id',
        'insumo_utilizado_id',
    ];

    // Relación con ServicioRealizado
    public function servicioRealizado()
    {
        return $this->belongsTo(ServicioRealizado::class, 'servicio_realizado_id');
    }

    // Relación con InsumoUtilizado
    public function insumoUtilizado()
    {
        return $this->belongsTo(InsumoUtilizado::class, 'insumo_utilizado_id');
    }
}
