<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    use HasFactory;

    protected $table = 'reclamos';

    protected $fillable = [
        'usuario_id',
        'fecha_registro',
        'cliente_id',
        'obra_id',
        'servicio_realizado_id',
        'estado_id',
        'observacion',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
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

    // Relación con ServicioRealizado
    public function servicioRealizado()
    {
        return $this->belongsTo(ServicioRealizado::class, 'servicio_realizado_id');
    }

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    // Relación con Detalles (fotos)
    public function fotos()
    {
        return $this->hasMany(ReclamoFoto::class, 'reclamo_id');
    }

    // Relación con Detalles (planos)
    public function planos()
    {
        return $this->hasMany(ReclamoPlano::class, 'reclamo_id');
    }
}
