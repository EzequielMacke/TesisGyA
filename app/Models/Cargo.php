<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargo';

    protected $fillable = [
        'descripcion',
        'estado_id',
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
