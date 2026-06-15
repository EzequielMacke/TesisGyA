<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudMaterial extends Model
{
    use HasFactory;

    protected $table = 'solicitud_materiales';

    protected $fillable = [
        'usuario_id',
        'obra_id',
        'deposito_id',
        'fecha',
        'estado_id',
        'observacion',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relación con Usuario (solicitante)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con Obra (destino)
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
    }

    // Relación con Deposito (destino)
    public function deposito()
    {
        return $this->belongsTo(Deposito::class, 'deposito_id');
    }

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    // Relación con Detalles
    public function detalles()
    {
        return $this->hasMany(SolicitudMaterialDetalle::class, 'solicitud_material_id');
    }

    // Solo se puede editar mientras esté activa y ningún detalle tenga cantidad entregada
    public function puedeEditarse(): bool
    {
        return $this->estado_id != 5
            && $this->detalles->every(fn ($detalle) => is_null($detalle->cantidad_entregada));
    }
}
