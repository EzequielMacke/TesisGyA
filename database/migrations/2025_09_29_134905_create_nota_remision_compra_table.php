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
        Schema::create('nota_remision_compra', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('deposito_id');
            $table->foreign('deposito_id')->references('id')->on('deposito');
            $table->string('nombre');
            $table->string('nro');
            $table->unsignedInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedor');
            $table->date('fecha_recepcion');
            $table->date('fecha_emision');
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->text('observacion')->nullable();
            $table->unsignedInteger('datos_empresa_id');
            $table->foreign('datos_empresa_id')->references('id')->on('datos_empresa');
            $table->string('conductor_nombre');
            $table->string('conductor_ci');
            $table->string('vehiculo_chapa');
            $table->unsignedInteger('tipo_vehiculo_id');
            $table->foreign('tipo_vehiculo_id')->references('id')->on('tipo_vehiculo');
            $table->unsignedInteger('orden_compra_id');
            $table->foreign('orden_compra_id')->references('id')->on('orden_compra');
            $table->string('origen');
            $table->string('destino');
            $table->unsignedInteger('recibido_por');
            $table->foreign('recibido_por')->references('id')->on('funcionarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_remision');
    }
};
