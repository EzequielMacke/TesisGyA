<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoCompraDetalleAprobado extends Model
{
    protected $table = 'presupuesto_compra_aprobado_detalles'; // ← CORREGIDO

    protected $fillable = [
        'pre_com_apr_id',
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
    public function presupuestoCompraAprobado()
    {
        return $this->belongsTo(PresupuestoCompraAprobado::class, 'pre_com_apr_id');
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
