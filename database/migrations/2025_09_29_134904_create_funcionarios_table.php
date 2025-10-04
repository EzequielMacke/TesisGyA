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
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_ingreso');
            $table->unsignedInteger('cargo_id');
            $table->foreign('cargo_id')->references('id')->on('cargo');
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('persona_id');
            $table->foreign('persona_id')->references('id')->on('persona');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
