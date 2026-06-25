<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReclamoPlano extends Model
{
    protected $table = 'reclamo_planos';

    protected $fillable = [
        'reclamo_id',
        'nombre_plano',
    ];

    // Relación con Reclamo
    public function reclamo()
    {
        return $this->belongsTo(Reclamo::class, 'reclamo_id');
    }
}
