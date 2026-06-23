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
        Schema::table('insumo_utilizado', function (Blueprint $table) {
            $table->dropForeign(['deposito_id']);
            $table->dropColumn('deposito_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insumo_utilizado', function (Blueprint $table) {
            $table->unsignedInteger('deposito_id')->after('fecha_registro');
            $table->foreign('deposito_id')->references('id')->on('deposito');
        });
    }
};
