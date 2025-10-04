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
        Schema::create('nota_remision_compra_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nota_remision_id');
            $table->foreign('nota_remision_id')->references('id')->on('nota_remision_compra');
            $table->unsignedInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumo');
            $table->decimal('cantidad_pedida', 10, 2);
            $table->decimal('cantidad_entregada', 10, 2);
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_remision_detalles');
    }
};
