<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoServicio extends Model
{
    protected $table = 'presupuesto_servicio';

    protected $fillable = [
        'cliente_id',
        'obra_id',
        'visita_previa_id',
        'numero_presupuesto',
        'descripcion',
        'monto',
        'estado_id',
        'fecha',
        'validez',
        'usuario_id',
        'anticipo',
        'observacion',
    ];

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

    // Relación con Visita Previa
    public function visitaPrevia()
    {
        return $this->belongsTo(VisitaPrevia::class, 'visita_previa_id');
    }

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    // Relación con Detalles
    public function detalles()
    {
        return $this->hasMany(PresupuestoServicioDetalle::class, 'presupuesto_servicio_id');
    }

}
