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
        Schema::create('compra_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('compra_id');
            $table->foreign('compra_id')->references('id')->on('compras');
            $table->unsignedInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumo');
            $table->decimal('precio_unitario', 10, 2);
            $table->unsignedInteger('impuesto_id');
            $table->foreign('impuesto_id')->references('id')->on('impuestos');
            $table->decimal('cantidad', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compra_detalle');
    }
};
