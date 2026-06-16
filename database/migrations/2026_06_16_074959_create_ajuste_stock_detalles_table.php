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
        Schema::create('ajuste_stock_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ajuste_stock_id');
            $table->foreign('ajuste_stock_id')->references('id')->on('ajuste_stocks');
            $table->unsignedInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumo');
            $table->decimal('cantidad', 10, 2);
            $table->string('motivo');
            $table->integer('tipo_ajuste');
            $table->string('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuste_stock_detalles');
    }
};
