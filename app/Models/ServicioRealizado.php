<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioRealizado extends Model
{
    use HasFactory;

    protected $table = 'servicio_realizado';

    protected $fillable = [
        'solicitud_servicio_id',
        'visita_previa_id',
        'presupuesto_servicio_id',
        'contrato_id',
        'orden_servicio_id',
        'cliente_id',
        'obra_id',
        'usuario_id',
        'fecha_registro',
        'estado_id',
        'observacion',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    // Relación con SolicitudServicio
    public function solicitudServicio()
    {
        return $this->belongsTo(SolicitudServicio::class, 'solicitud_servicio_id');
    }

    // Relación con VisitaPrevia
    public function visitaPrevia()
    {
        return $this->belongsTo(VisitaPrevia::class, 'visita_previa_id');
    }

    // Relación con PresupuestoServicio
    public function presupuestoServicio()
    {
        return $this->belongsTo(PresupuestoServicio::class, 'presupuesto_servicio_id');
    }

    // Relación con Contrato
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    // Relación con OrdenServicio
    public function ordenServicio()
    {
        return $this->belongsTo(OrdenServicio::class, 'orden_servicio_id');
    }

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación con Obra
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    // Relación con Detalles (insumos)
    public function insumos()
    {
        return $this->hasMany(ServicioRealizadoInsumo::class, 'servicio_realizado_id');
    }

    // Relación con Detalles (fotos)
    public function fotos()
    {
        return $this->hasMany(ServicioRealizadoFoto::class, 'servicio_realizado_id');
    }

    // Relación con Detalles (planos)
    public function planos()
    {
        return $this->hasMany(ServicioRealizadoPlano::class, 'servicio_realizado_id');
    }
}
