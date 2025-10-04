<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibroCompra extends Model
{
    use HasFactory;

    protected $table = 'libro_compras';

    protected $fillable = [
        'proveedor_id',
        'compra_id',
        'tipo_documento_id',
        'monto',
        'iva5',
        'iva10',
        'iva_exento',
        'total_iva',
        'fecha_emision',
        'condicion_pago_id',
        'estado_id',
        'datos_empresa_id',
        'timbrado',
        'nro_factura',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    public function condicionPago()
    {
        return $this->belongsTo(CondicionPago::class, 'condicion_pago_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function datosEmpresa()
    {
        return $this->belongsTo(DatoEmpresa::class, 'datos_empresa_id');
    }
}
