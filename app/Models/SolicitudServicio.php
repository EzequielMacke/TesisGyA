<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudServicio extends Model
{
    protected $table = 'solicitud_servicio';

    protected $fillable = [
        'usuario_id',
        'cliente_id',
        'obra_id',
        'estado_id',
        'observacion',
        'fecha',
    ];

    // Relación con Usuario (quien registró la solicitud)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación con Obra
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
    }

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

      public function detalles()
    {
        return $this->hasMany(SolicitudServicioDetalle::class, 'solicitud_servicio_id');
    }

}
