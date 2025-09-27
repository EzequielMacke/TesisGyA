<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $table = 'insumo';

    protected $fillable = [
        'descripcion',
        'codigo',
        'marca_id',
        'unidad_medida_id',
        'usuario_id',
        'sucursal_id',
        'estado_id',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

    // Relación con Marca
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    // Relación con UnidadMedida
    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    // Relación con Sucursal (si existe)
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
