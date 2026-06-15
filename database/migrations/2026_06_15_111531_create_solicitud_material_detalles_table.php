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
        Schema::create('solicitud_material_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('solicitud_material_id');
            $table->foreign('solicitud_material_id')->references('id')->on('solicitud_materiales');
            $table->unsignedInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumo');
            $table->decimal('cantidad_solicitada', 10, 2);
            $table->decimal('cantidad_entregada', 10, 2)->nullable();
            $table->string('observacion')->nullable();
            $table->integer('terminado')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_material_detalles');
    }
};
