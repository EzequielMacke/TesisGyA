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
        Schema::create('compras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nro_factura');
            $table->string('nro_timbrado');
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->unsignedInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedor');
            $table->unsignedInteger('condicion_pago_id');
            $table->foreign('condicion_pago_id')->references('id')->on('condicion_pago');
            $table->unsignedInteger('metodo_pago_id');
            $table->foreign('metodo_pago_id')->references('id')->on('metodo_pago');
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->unsignedInteger('orden_compra_id');
            $table->foreign('orden_compra_id')->references('id')->on('orden_compra');
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->string('observacion')->nullable();
            $table->decimal('monto', 10, 2);
            $table->unsignedInteger('datos_empresa_id');
            $table->foreign('datos_empresa_id')->references('id')->on('datos_empresa');
            $table->unsignedInteger('presupuesto_compra_aprobado_id');
            $table->foreign('presupuesto_compra_aprobado_id')->references('id')->on('presupuesto_compra_aprobados');
            $table->unsignedInteger('tipo_documento_id');
            $table->foreign('tipo_documento_id')->references('id')->on('tipo_documento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
