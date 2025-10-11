<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    protected $fillable = [
        'cliente_id',
        'obra_id',
        'presupuesto_servicio_id',
        'usuario_id',
        'plazo_dias',
        'fecha_firma',
        'fecha_registro',
        'monto',
        'anticipo',
        'estado_id',
        'pago_mitad',
        'pago_final',
        'observaciones',
        'garantia_meses',
        'ciudad',
    ];

    protected $casts = [
        'fecha_firma' => 'date',
        'monto' => 'decimal:2',
        'anticipo' => 'decimal:2',
        'pago_mitad' => 'decimal:2',
        'pago_final' => 'decimal:2',
        'garantia_meses' => 'decimal:2',
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

    // Relación con PresupuestoServicio
    public function presupuestoServicio()
    {
        return $this->belongsTo(PresupuestoServicio::class, 'presupuesto_servicio_id');
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
}
