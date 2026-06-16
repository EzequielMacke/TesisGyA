<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjusteStockDetalle extends Model
{
    use HasFactory;

    protected $table = 'ajuste_stock_detalles';

    protected $fillable = [
        'ajuste_stock_id',
        'insumo_id',
        'cantidad',
        'motivo',
        'tipo_ajuste',
        'observacion',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'tipo_ajuste' => 'integer',
    ];

    // Relación con AjusteStock
    public function ajusteStock()
    {
        return $this->belongsTo(AjusteStock::class, 'ajuste_stock_id');
    }

    // Relación con Insumo
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
}
