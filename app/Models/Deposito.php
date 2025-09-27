<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    use HasFactory;

    protected $table = 'deposito';

    protected $fillable = [
        'descripcion',
        'sucursal_id',
        'estado_id'
    ];

    // Relación con Sucursal
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    // Relación con Inventarios
    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }
}
