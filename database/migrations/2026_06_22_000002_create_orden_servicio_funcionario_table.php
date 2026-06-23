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
        Schema::create('orden_servicio_funcionario', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('orden_servicio_id');
            $table->foreign('orden_servicio_id')->references('id')->on('orden_servicio');
            $table->unsignedInteger('funcionario_id');
            $table->foreign('funcionario_id')->references('id')->on('funcionarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_servicio_funcionario');
    }
};
