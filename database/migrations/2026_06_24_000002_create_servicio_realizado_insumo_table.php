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
        Schema::create('servicio_realizado_insumo', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('servicio_realizado_id');
            $table->foreign('servicio_realizado_id')->references('id')->on('servicio_realizado');
            $table->unsignedInteger('insumo_utilizado_id');
            $table->foreign('insumo_utilizado_id')->references('id')->on('insumo_utilizado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_realizado_insumo');
    }
};
