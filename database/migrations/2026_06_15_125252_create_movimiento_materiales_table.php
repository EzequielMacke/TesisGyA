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
        Schema::create('movimiento_materiales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nro_remision');
            $table->unsignedInteger('origen_deposito_id');
            $table->foreign('origen_deposito_id')->references('id')->on('deposito');
            $table->unsignedInteger('destino_obra_id')->nullable();
            $table->foreign('destino_obra_id')->references('id')->on('obras');
            $table->unsignedInteger('destino_deposito_id')->nullable();
            $table->foreign('destino_deposito_id')->references('id')->on('deposito');
            $table->unsignedInteger('solicitud_material_id');
            $table->foreign('solicitud_material_id')->references('id')->on('solicitud_materiales');
            $table->string('vehiculo_chapa');
            $table->unsignedInteger('tipo_vehiculo_id');
            $table->foreign('tipo_vehiculo_id')->references('id')->on('tipo_vehiculo');
            $table->string('chofer_ci');
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
        Schema::dropIfExists('movimiento_materiales');
    }
};
