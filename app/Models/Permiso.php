<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permiso';

    protected $fillable = [
        'modulo_id',
        'ver',
        'agregar',
        'editar',
        'anular',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }
}
