<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitaPreviaFoto extends Model
{
    protected $table = 'visita_previa_fotos';

    protected $fillable = [
        'visita_previa_id',
        'ruta_foto',
        'fecha',
        'usuario_id',
        'obra_id',
    ];

    // Relación con VisitaPrevia
    public function visitaPrevia()
    {
        return $this->belongsTo(VisitaPrevia::class, 'visita_previa_id');
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con Obra
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
    }
}
