<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'orden_compra';

    protected $fillable = [
        'usuario_id',
        'proveedor_id',
        'condicion_pago_id',
        'estado_id',
        'metodo_pago_id',
        'fecha',
        'monto',
        'presupuesto_compra_aprobado_id',
        'intervalo',
        'cuota',
        'observacion'
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
        'intervalo' => 'integer',
        'cuota' => 'integer'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function condicionPago()
    {
        return $this->belongsTo(CondicionPago::class, 'condicion_pago_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function presupuestoCompraAprobado()
    {
        return $this->belongsTo(PresupuestoCompraAprobado::class, 'presupuesto_compra_aprobado_id');
    }
}
