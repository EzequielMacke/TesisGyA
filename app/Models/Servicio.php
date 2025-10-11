<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'descripcion',
        'estado_id',
    ];

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
    public function ensayos()
    {
        return $this->hasMany(Ensayo::class, 'servicio_id');
    }
}
