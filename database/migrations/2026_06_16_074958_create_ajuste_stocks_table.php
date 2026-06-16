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
        Schema::create('ajuste_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('obra_id')->nullable();
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->unsignedInteger('deposito_id')->nullable();
            $table->foreign('deposito_id')->references('id')->on('deposito');
            $table->string('observacion')->nullable();
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->date('fecha');
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuste_stocks');
    }
};
