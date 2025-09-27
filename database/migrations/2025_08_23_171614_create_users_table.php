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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sucursal_id')->nullable();
            $table->foreign('sucursal_id')->references('id')->on('sucursal');
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->string('email')->unique();
            $table->integer('verificado')->nullable();
            $table->integer('acceso_intento')->default(0);
            $table->unsignedInteger('persona_id')->nullable();
            $table->foreign('persona_id')->references('id')->on('persona');
            $table->unsignedInteger('empleado_id')->nullable();
            $table->foreign('empleado_id')->references('id')->on('empleado');
            $table->string('usuario');
            $table->string('contraseña');
            $table->string('codigo_verificacion')->nullable();
            $table->string('codigo_autenticacion')->nullable();
            $table->unsignedInteger('cargo_id')->nullable();
            $table->foreign('cargo_id')->references('id')->on('cargo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
