<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario';

    protected $fillable = [
        'deposito_id',
        'insumo_id',
        'cantidad',
        'estado_id'
    ];

    protected $casts = [
        'cantidad' => 'decimal:2'
    ];

    // Relación con Deposito
    public function deposito()
    {
        return $this->belongsTo(Deposito::class);
    }

    // Relación con Insumo
    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}
