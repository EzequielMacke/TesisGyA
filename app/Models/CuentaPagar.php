<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaPagar extends Model
{
    use HasFactory;

    protected $table = 'cuenta_pagar';

    protected $fillable = [
        'compra_id',
        'cuota',
        'metodo_pago_id',
        'condicion_pago_id',
        'proveedor_id',
        'fecha_emision',
        'fecha_pago',
        'fecha_vencimiento',
        'monto',
        'monto_pagado',
        'saldo',
        'estado_id',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_pago' => 'date',
        'fecha_vencimiento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'monto' => 'float',
        'monto_pagado' => 'float',
        'saldo' => 'float',
    ];

    // Relación con la compra
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    // Relación con el proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    // Relación con el método de pago
    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    // Relación con la condición de pago
    public function condicionPago()
    {
        return $this->belongsTo(CondicionPago::class, 'condicion_pago_id');
    }

    // Relación con el estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
