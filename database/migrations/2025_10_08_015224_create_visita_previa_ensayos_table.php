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
        Schema::create('visita_previa_ensayos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('visita_previa_id');
            $table->foreign('visita_previa_id')->references('id')->on('visita_previa');
            $table->unsignedInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios');
            $table->unsignedInteger('ensayo_id');
            $table->foreign('ensayo_id')->references('id')->on('ensayos');
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->unsignedInteger('obra_id');
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->date('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visita_previa_ensayos');
    }
};
