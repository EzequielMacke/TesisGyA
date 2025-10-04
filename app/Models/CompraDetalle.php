<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraDetalle extends Model
{
    protected $table = 'compra_detalle';

    protected $fillable = [
        'compra_id',
        'insumo_id',
        'precio_unitario',
        'impuesto_id',
        'cantidad',
    ];

    // Relación con la compra
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }



    // Relación con el insumo
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
    public function impuesto()
    {
        return $this->belongsTo(Impuesto::class, 'impuesto_id');
    }
}
