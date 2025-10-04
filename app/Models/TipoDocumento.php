<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tipo_documento';

    protected $fillable = [
        'descripcion',
        'estado_id',
    ];

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }


}
