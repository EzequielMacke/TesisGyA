<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model
{
    use HasFactory;

    protected $table = 'orden_servicio';

    protected $fillable = [
        'nro',
        'datos_empresa_id',
        'contrato_id',
        'presupuesto_servicio_id',
        'cliente_id',
        'obra_id',
        'estado_id',
        'fecha_registro',
        'fecha_culminacion_teorica',
        'fecha_culminacion_real',
        'observacion',
        'usuario_id',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'fecha_culminacion_teorica' => 'date',
        'fecha_culminacion_real' => 'date',
    ];

    // Relación con Datos de la Empresa
    public function datosEmpresa()
    {
        return $this->belongsTo(DatoEmpresa::class, 'datos_empresa_id');
    }

    // Relación con Contrato
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    // Relación con PresupuestoServicio
    public function presupuestoServicio()
    {
        return $this->belongsTo(PresupuestoServicio::class, 'presupuesto_servicio_id');
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

    // Relación con Funcionarios asignados
    public function funcionarios()
    {
        return $this->hasMany(OrdenServicioFuncionario::class, 'orden_servicio_id');
    }

    // Relación con Detalles (ensayos)
    public function detalles()
    {
        return $this->hasMany(OrdenServicioDetalle::class, 'orden_servicio_id');
    }
}
