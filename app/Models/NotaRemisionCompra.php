<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaRemisionCompra extends Model
{
    use HasFactory;

    protected $table = 'nota_remision_compra';

    protected $fillable = [
        'deposito_id',
        'nombre',
        'nro',
        'proveedor_id',
        'fecha_recepcion',
        'fecha_emision',
        'usuario_id',
        'estado_id',
        'observacion',
        'datos_empresa_id',
        'conductor_nombre',
        'conductor_ci',
        'vehiculo_chapa',
        'tipo_vehiculo_id',
        'orden_compra_id',
        'origen',
        'destino',
        'recibido_por'
    ];

    protected $casts = [
        'fecha_recepcion' => 'date',
        'fecha_emision' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relaciones
    public function deposito()
    {
        return $this->belongsTo(Deposito::class, 'deposito_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function datosEmpresa()
    {
        return $this->belongsTo(DatoEmpresa::class, 'datos_empresa_id');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'tipo_vehiculo_id');
    }

    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class, 'orden_compra_id');
    }

    public function recibidoPor()
    {
        return $this->belongsTo(Funcionario::class, 'recibido_por');
    }
    public function detalles()
    {
        return $this->hasMany(NotaRemisionCompraDetalle::class, 'nota_remision_id');
    }
}
