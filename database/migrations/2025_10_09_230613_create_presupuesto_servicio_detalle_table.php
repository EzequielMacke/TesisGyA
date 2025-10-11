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
        Schema::create('presupuesto_servicio_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('presupuesto_servicio_id');
            $table->foreign('presupuesto_servicio_id')->references('id')->on('presupuesto_servicio');
            $table->unsignedInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios');
            $table->unsignedInteger('ensayos_id');
            $table->foreign('ensayos_id')->references('id')->on('ensayos');
            $table->decimal('precio_unitario', 10, 2);
            $table->unsignedInteger('impuesto_id');
            $table->foreign('impuesto_id')->references('id')->on('impuestos');
            $table->text('observacion')->nullable();
            $table->integer('cantidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuesto_servicio_detalle');
    }
};
