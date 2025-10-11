<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitaPrevia extends Model
{
    protected $table = 'visita_previa';

    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'obra_id',
        'fecha_visita',
        'observacion',
        'estado_id',
        'solicitud_servicio_id',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function planos()
    {
        return $this->hasMany(VisitaPreviaPlano::class, 'visita_previa_id');
    }

    public function fotos()
    {
        return $this->hasMany(VisitaPreviaFoto::class, 'visita_previa_id');
    }
    public function solicitudServicio()
    {
        return $this->belongsTo(SolicitudServicio::class, 'solicitud_servicio_id');
    }
    public function ensayos()
    {
        return $this->hasMany(VisitaPreviaEnsayos::class, 'visita_previa_id');
    }
}
