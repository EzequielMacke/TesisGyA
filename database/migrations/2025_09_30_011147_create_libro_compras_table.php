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
        Schema::create('libro_compras', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedor');
            $table->unsignedInteger('compra_id');
            $table->foreign('compra_id')->references('id')->on('compras');
            $table->unsignedInteger('tipo_documento_id');
            $table->foreign('tipo_documento_id')->references('id')->on('tipo_documento');
            $table->decimal('monto', 10, 2);
            $table->decimal('iva5', 10, 2);
            $table->decimal('iva10', 10, 2);
            $table->decimal('iva_exento', 10, 2);
            $table->decimal('total_iva', 10, 2);
            $table->date('fecha_emision');
            $table->unsignedInteger('condicion_pago_id');
            $table->foreign('condicion_pago_id')->references('id')->on('condicion_pago');
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->unsignedInteger('datos_empresa_id');
            $table->foreign('datos_empresa_id')->references('id')->on('datos_empresa');
            $table->string('timbrado');
            $table->string('nro_factura');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libro_compras');
    }
};
