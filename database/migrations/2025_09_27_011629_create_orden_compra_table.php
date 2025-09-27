<?php

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
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
        Schema::create('orden_compra', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->unsignedInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedor');
            $table->unsignedInteger('condicion_pago_id');
            $table->foreign('condicion_pago_id')->references('id')->on('condicion_pago');
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->unsignedInteger('metodo_pago_id');
            $table->foreign('metodo_pago_id')->references('id')->on('metodo_pago');
            $table->date('fecha');
            $table->decimal('monto', 10, 2);
            $table->unsignedInteger('presupuesto_compra_aprobado_id');
            $table->foreign('presupuesto_compra_aprobado_id')->references('id')->on('presupuesto_compra_aprobados');
            $table->integer('intervalo');
            $table->integer('cuota');
            $table->string('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_compra');
    }
};
