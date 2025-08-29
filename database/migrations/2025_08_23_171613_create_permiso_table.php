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
        Schema::create('permiso', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('modulo_id');
            $table->foreign('modulo_id')->references('id')->on('modulo');
            $table->integer('ver');
            $table->integer('agregar');
            $table->integer('editar');
            $table->integer('anular');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permiso');
    }
};
