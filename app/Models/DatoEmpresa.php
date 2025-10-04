<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatoEmpresa extends Model
{
    use HasFactory;

    protected $table = 'datos_empresa';

    protected $fillable = [
        'razon_social',
        'ruc',
        'direccion',
        'telefono',
        'email',
        'estado_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
