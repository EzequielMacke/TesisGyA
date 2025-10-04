<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaRemisionCompraDetalle extends Model
{
    use HasFactory;

    protected $table = 'nota_remision_compra_detalles';

    protected $fillable = [
        'nota_remision_id',
        'insumo_id',
        'cantidad_pedida',
        'cantidad_entregada',
        'observacion'
    ];

    protected $casts = [
        'cantidad_pedida' => 'decimal:2',
        'cantidad_entregada' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relaciones
    public function notaRemision()
    {
        return $this->belongsTo(NotaRemisionCompra::class, 'nota_remision_id');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
}
