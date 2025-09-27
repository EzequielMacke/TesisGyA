<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoCompraDetalle extends Model
{
    use HasFactory;

    protected $table = 'pedido_compra_detalles';

    protected $fillable = [
        'pedido_compra_id',
        'insumo_id',
        'cantidad',
        'observacion',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
    ];

    // Relación con PedidoCompra
    public function pedidoCompra()
    {
        return $this->belongsTo(PedidoCompra::class);
    }

    // Relación con Insumo
    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
}
