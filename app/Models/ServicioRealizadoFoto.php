<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioRealizadoFoto extends Model
{
    protected $table = 'servicio_realizado_fotos';

    protected $fillable = [
        'servicio_realizado_id',
        'nombre_foto',
    ];

    // Relación con ServicioRealizado
    public function servicioRealizado()
    {
        return $this->belongsTo(ServicioRealizado::class, 'servicio_realizado_id');
    }
}
