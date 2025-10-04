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
        Schema::create('cuenta_pagar', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('compra_id');
            $table->foreign('compra_id')->references('id')->on('compras');
            $table->integer('cuota');
            $table->unsignedInteger('metodo_pago_id');
            $table->foreign('metodo_pago_id')->references('id')->on('metodo_pago');
            $table->unsignedInteger('condicion_pago_id');
            $table->foreign('condicion_pago_id')->references('id')->on('condicion_pago');
            $table->unsignedInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedor');
            $table->date('fecha_emision');
            $table->date('fecha_pago')->nullable();
            $table->date('fecha_vencimiento');
            $table->decimal('monto', 10, 2);
            $table->decimal('monto_pagado', 10, 2)->nullable();
            $table->decimal('saldo', 10, 2)->nullable();
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_pagar');
    }
};
