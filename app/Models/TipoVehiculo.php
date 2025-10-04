<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    use HasFactory;

    protected $table = 'tipo_vehiculo';

    protected $fillable = [
        'descripcion',
        'estado_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }


}
