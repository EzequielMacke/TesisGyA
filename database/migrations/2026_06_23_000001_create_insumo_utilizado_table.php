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
        Schema::create('insumo_utilizado', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('orden_servicio_id');
            $table->foreign('orden_servicio_id')->references('id')->on('orden_servicio');
            $table->unsignedInteger('obra_id');
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->date('fecha_registro');
            $table->unsignedInteger('deposito_id');
            $table->foreign('deposito_id')->references('id')->on('deposito');
            $table->string('nro', 7)->unique();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumo_utilizado');
    }
};
