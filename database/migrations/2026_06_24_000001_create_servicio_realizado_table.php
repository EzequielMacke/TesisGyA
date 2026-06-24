<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servicio_realizado', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('solicitud_servicio_id');
            $table->foreign('solicitud_servicio_id')->references('id')->on('solicitud_servicio');
            $table->unsignedInteger('visita_previa_id');
            $table->foreign('visita_previa_id')->references('id')->on('visita_previa');
            $table->unsignedInteger('presupuesto_servicio_id');
            $table->foreign('presupuesto_servicio_id')->references('id')->on('presupuesto_servicio');
            $table->unsignedInteger('contrato_id');
            $table->foreign('contrato_id')->references('id')->on('contratos');
            $table->unsignedInteger('orden_servicio_id');
            $table->foreign('orden_servicio_id')->references('id')->on('orden_servicio');
            $table->unsignedInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->unsignedInteger('obra_id');
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->date('fecha_registro');
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_realizado');
    }
};
