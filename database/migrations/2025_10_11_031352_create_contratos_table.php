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
        Schema::create('contratos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->unsignedInteger('obra_id');
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->unsignedInteger('presupuesto_servicio_id');
            $table->foreign('presupuesto_servicio_id')->references('id')->on('presupuesto_servicio');
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->integer('plazo_dias');
            $table->date('fecha_firma');
            $table->date('fecha_registro');
            $table->decimal('monto', 10, 2);
            $table->decimal('anticipo', 10, 2);
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->decimal('pago_mitad', 10, 2);
            $table->decimal('pago_final', 10, 2);
            $table->text('observaciones')->nullable();
            $table->decimal('garantia_meses', 10, 2);
            $table->string('ciudad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
