<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';

    protected $fillable = [
        'nro_factura',
        'nro_timbrado',
        'fecha_emision',
        'fecha_vencimiento',
        'proveedor_id',
        'condicion_pago_id',
        'metodo_pago_id',
        'usuario_id',
        'orden_compra_id',
        'estado_id',
        'observacion',
        'monto',
        'datos_empresa_id',
        'presupuesto_compra_aprobado_id',
        'tipo_documento_id',
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function condicionPago()
    {
        return $this->belongsTo(CondicionPago::class, 'condicion_pago_id');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class, 'orden_compra_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function datosEmpresa()
    {
        return $this->belongsTo(DatoEmpresa::class, 'datos_empresa_id');
    }

    public function presupuestoCompraAprobado()
    {
        return $this->belongsTo(PresupuestoCompraAprobado::class, 'presupuesto_compra_aprobado_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }


}
