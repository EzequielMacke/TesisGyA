<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioRealizadoPlano extends Model
{
    protected $table = 'servicio_realizado_planos';

    protected $fillable = [
        'servicio_realizado_id',
        'nombre_plano',
    ];

    // Relación con ServicioRealizado
    public function servicioRealizado()
    {
        return $this->belongsTo(ServicioRealizado::class, 'servicio_realizado_id');
    }
}
