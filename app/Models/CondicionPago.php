<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondicionPago extends Model
{
    use HasFactory;

    protected $table = 'condicion_pago';

    protected $fillable = [
        'descripcion',
        'estado_id'
    ];

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
