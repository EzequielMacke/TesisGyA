<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReclamoFoto extends Model
{
    protected $table = 'reclamo_fotos';

    protected $fillable = [
        'reclamo_id',
        'nombre_foto',
    ];

    // Relación con Reclamo
    public function reclamo()
    {
        return $this->belongsTo(Reclamo::class, 'reclamo_id');
    }
}
