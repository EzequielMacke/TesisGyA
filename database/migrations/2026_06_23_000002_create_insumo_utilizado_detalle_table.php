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
        Schema::create('insumo_utilizado_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('insumo_utilizado_id');
            $table->foreign('insumo_utilizado_id')->references('id')->on('insumo_utilizado');
            $table->unsignedInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumo');
            $table->decimal('cantidad', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumo_utilizado_detalle');
    }
};
