<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaCompra extends Model
{
    protected $table = 'nota_compra';

    protected $fillable = [
        'nro_nota',
        'timbrado',
        'fecha_emision',
        'fecha_vencimiento',
        'proveedor_id',
        'factura_id',
        'monto',
        'iva_id',
        'tipo_documento_id',
        'concepto',
        'usuario_id',
        'estado_id',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'monto' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function factura()
    {
        return $this->belongsTo(Compra::class, 'factura_id');
    }

    public function iva()
    {
        return $this->belongsTo(Impuesto::class, 'iva_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
