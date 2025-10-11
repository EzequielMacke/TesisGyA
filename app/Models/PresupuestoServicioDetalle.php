<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoServicioDetalle extends Model
{
    protected $table = 'presupuesto_servicio_detalle';

    protected $fillable = [
        'presupuesto_servicio_id',
        'servicio_id',
        'ensayos_id',
        'precio_unitario',
        'impuesto_id',
        'observacion',
        'cantidad',
    ];

    // Relación con PresupuestoServicio
    public function presupuestoServicio()
    {
        return $this->belongsTo(PresupuestoServicio::class, 'presupuesto_servicio_id');
    }

    // Relación con Servicio
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    // Relación con Ensayo
    public function ensayo()
    {
        return $this->belongsTo(Ensayo::class, 'ensayos_id');
    }

    // Relación con Impuesto
    public function impuesto()
    {
        return $this->belongsTo(Impuesto::class, 'impuesto_id');
    }
}
