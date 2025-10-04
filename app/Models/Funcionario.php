<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    protected $table = 'funcionarios';

    protected $fillable = [
        'fecha_ingreso',
        'cargo_id',
        'estado_id',
        'user_id',
        'persona_id'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    // Relación con Cargo
    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }
}
