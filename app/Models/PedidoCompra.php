<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoCompra extends Model
{
    use HasFactory;

    protected $table = 'pedido_compras';

    protected $fillable = [
        'usuario_id',
        'deposito_id',
        'sucursal_id',
        'estado_id',
        'fecha',
        'observacion'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con Deposito
    public function deposito()
    {
        return $this->belongsTo(Deposito::class);
    }

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
    public function detalles()
    {
        return $this->hasMany(PedidoCompraDetalle::class);
    }
    public function presupuestos()
    {
        return $this->hasMany(PresupuestoCompra::class, 'pedido_compra_id');
    }
}
