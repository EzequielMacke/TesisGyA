<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjusteStock extends Model
{
    use HasFactory;

    protected $table = 'ajuste_stocks';

    protected $fillable = [
        'obra_id',
        'deposito_id',
        'observacion',
        'estado_id',
        'fecha',
        'usuario_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relación con Obra
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
    }

    // Relación con Deposito
    public function deposito()
    {
        return $this->belongsTo(Deposito::class, 'deposito_id');
    }

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con Detalles
    public function detalles()
    {
        return $this->hasMany(AjusteStockDetalle::class, 'ajuste_stock_id');
    }
}
