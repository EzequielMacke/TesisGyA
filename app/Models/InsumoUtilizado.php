<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsumoUtilizado extends Model
{
    use HasFactory;

    protected $table = 'insumo_utilizado';

    protected $fillable = [
        'orden_servicio_id',
        'obra_id',
        'estado_id',
        'usuario_id',
        'fecha_registro',
        'nro',
        'observacion',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    // Relación con OrdenServicio
    public function ordenServicio()
    {
        return $this->belongsTo(OrdenServicio::class, 'orden_servicio_id');
    }

    // Relación con Obra
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
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

    // Relación con Detalles (insumos)
    public function detalles()
    {
        return $this->hasMany(InsumoUtilizadoDetalle::class, 'insumo_utilizado_id');
    }
}
