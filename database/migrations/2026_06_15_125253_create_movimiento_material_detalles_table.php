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
        Schema::create('movimiento_material_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('movimiento_material_id');
            $table->foreign('movimiento_material_id')->references('id')->on('movimiento_materiales');
            $table->unsignedInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumo');
            $table->decimal('cantidad', 10, 2);
            $table->string('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_material_detalles');
    }
};
