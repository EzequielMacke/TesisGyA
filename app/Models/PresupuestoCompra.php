<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoCompra extends Model
{
    protected $table = 'presupuesto_compras';

    protected $fillable = [
        'nombre',
        'descripcion',
        'proveedor_id',
        'validez',
        'fecha_emision',
        'fecha_vencimiento',
        'estado_id',
        'usuario_id',
        'pedido_compra_id'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'validez' => 'integer'
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function pedidoCompra()
    {
        return $this->belongsTo(PedidoCompra::class, 'pedido_compra_id');
    }

    public function detalles()
    {
        return $this->hasMany(PresupuestoCompraDetalle::class, 'presupuesto_compra_id');
    }
}
