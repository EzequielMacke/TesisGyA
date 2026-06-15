<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoMaterial extends Model
{
    use HasFactory;

    protected $table = 'movimiento_materiales';

    protected $fillable = [
        'usuario_id',
        'nro_remision',
        'fecha',
        'origen_deposito_id',
        'destino_obra_id',
        'destino_deposito_id',
        'solicitud_material_id',
        'vehiculo_chapa',
        'tipo_vehiculo_id',
        'chofer_ci',
        'chofer_nombre',
        'estado_id',
        'observacion',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con Deposito (origen)
    public function origenDeposito()
    {
        return $this->belongsTo(Deposito::class, 'origen_deposito_id');
    }

    // Relación con Obra (destino)
    public function destinoObra()
    {
        return $this->belongsTo(Obra::class, 'destino_obra_id');
    }

    // Relación con Deposito (destino)
    public function destinoDeposito()
    {
        return $this->belongsTo(Deposito::class, 'destino_deposito_id');
    }

    // Relación con Solicitud de Materiales
    public function solicitudMaterial()
    {
        return $this->belongsTo(SolicitudMaterial::class, 'solicitud_material_id');
    }

    // Relación con Tipo de Vehículo
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'tipo_vehiculo_id');
    }

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    // Relación con Detalles
    public function detalles()
    {
        return $this->hasMany(MovimientoMaterialDetalle::class, 'movimiento_material_id');
    }
}
