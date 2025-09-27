<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoCompraDetalle extends Model
{
    protected $table = 'presupuesto_compra_detalles';

    protected $fillable = [
        'presupuesto_compra_id',
        'insumo_id',
        'cantidad',
        'precio_unitario',
        'observacion',
        'impuesto_id'
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_unitario' => 'decimal:2'
    ];

    // Relaciones
    public function presupuestoCompra()
    {
        return $this->belongsTo(PresupuestoCompra::class, 'presupuesto_compra_id');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    public function impuesto()
    {
        return $this->belongsTo(Impuesto::class, 'impuesto_id');
    }
}
