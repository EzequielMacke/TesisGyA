<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenServicioDetalle extends Model
{
    protected $table = 'orden_servicio_detalle';

    protected $fillable = [
        'orden_servicio_id',
        'ensayo_id',
    ];

    // Relación con OrdenServicio
    public function ordenServicio()
    {
        return $this->belongsTo(OrdenServicio::class, 'orden_servicio_id');
    }

    // Relación con Ensayo
    public function ensayo()
    {
        return $this->belongsTo(Ensayo::class, 'ensayo_id');
    }
}
