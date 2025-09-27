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
        Schema::create('presupuesto_compra_aprobado_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pre_com_apr_id');
            $table->foreign('pre_com_apr_id')->references('id')->on('presupuesto_compra_aprobados');
            $table->unsignedInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumo');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio_unitario', 10, 2);
            $table->string('observacion')->nullable();
            $table->unsignedInteger('impuesto_id');
            $table->foreign('impuesto_id')->references('id')->on('impuestos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuesto_compra_aprobado_detalles');
    }
};
