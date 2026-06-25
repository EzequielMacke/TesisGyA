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
        Schema::create('reclamo_fotos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('reclamo_id');
            $table->foreign('reclamo_id')->references('id')->on('reclamos');
            $table->string('nombre_foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamo_fotos');
    }
};
