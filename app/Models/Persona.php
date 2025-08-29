<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';

    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'direccion',
        'telefono',
        'fecha_nacimiento',
        'genero',
        'estado_id',
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
