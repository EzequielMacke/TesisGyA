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
        Schema::create('orden_servicio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nro', 7);
            $table->unsignedInteger('datos_empresa_id')->default(1);
            $table->foreign('datos_empresa_id')->references('id')->on('datos_empresa');
            $table->unsignedInteger('contrato_id');
            $table->foreign('contrato_id')->references('id')->on('contratos');
            $table->unsignedInteger('presupuesto_servicio_id');
            $table->foreign('presupuesto_servicio_id')->references('id')->on('presupuesto_servicio');
            $table->unsignedInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->unsignedInteger('obra_id');
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->unsignedInteger('estado_id')->default(3);
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->date('fecha_registro');
            $table->date('fecha_culminacion_teorica');
            $table->date('fecha_culminacion_real')->nullable();
            $table->text('observacion')->nullable();
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_servicio');
    }
};
