<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    protected $table = 'impuestos';

    protected $fillable = [
        'descripcion',
        'calculo',
        'estado_id'
    ];

    protected $casts = [
        'calculo' => 'decimal:2'
    ];

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
