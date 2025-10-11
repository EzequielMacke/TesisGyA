<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudServicioDetalle extends Model
{
    protected $table = 'solicitud_servicio_detalle';

    protected $fillable = [
        'solicitud_servicio_id',
        'servicio_id',
        'observacion',
    ];

    // Relación con SolicitudServicio
    public function solicitudServicio()
    {
        return $this->belongsTo(SolicitudServicio::class, 'solicitud_servicio_id');
    }

    // Relación con Servicio
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}
