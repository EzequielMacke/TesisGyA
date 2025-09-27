<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'unidad_medidas';

    protected $fillable = [
        'descripcion',
        'fecha',
        'estado_id',
        'usuario_id'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
