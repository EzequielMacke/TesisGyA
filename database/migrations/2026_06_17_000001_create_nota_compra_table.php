<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nota_compra', function (Blueprint $table) {
            $table->id();
            $table->string('nro_nota');
            $table->integer('timbrado');
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->unsignedInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedor');
            $table->unsignedInteger('factura_id');
            $table->foreign('factura_id')->references('id')->on('compras');
            $table->decimal('monto', 15, 2);
            $table->unsignedInteger('iva_id');
            $table->foreign('iva_id')->references('id')->on('impuestos');
            $table->unsignedInteger('tipo_documento_id');
            $table->foreign('tipo_documento_id')->references('id')->on('tipo_documento');
            $table->string('concepto')->nullable();
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nota_compra');
    }
};
