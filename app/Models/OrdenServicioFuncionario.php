<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenServicioFuncionario extends Model
{
    protected $table = 'orden_servicio_funcionario';

    protected $fillable = [
        'orden_servicio_id',
        'funcionario_id',
    ];

    // Relación con OrdenServicio
    public function ordenServicio()
    {
        return $this->belongsTo(OrdenServicio::class, 'orden_servicio_id');
    }

    // Relación con Funcionario
    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }
}
