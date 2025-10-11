<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitaPreviaEnsayos extends Model
{
    protected $table = 'visita_previa_ensayos';

    protected $fillable = [
        'visita_previa_id',
        'servicio_id',
        'ensayo_id',
        'usuario_id',
        'obra_id',
        'estado_id',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones
    public function visitaPrevia()
    {
        return $this->belongsTo(VisitaPrevia::class, 'visita_previa_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function ensayo()
    {
        return $this->belongsTo(Ensayo::class, 'ensayo_id');
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
}
