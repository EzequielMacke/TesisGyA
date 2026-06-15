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
        Schema::table('movimiento_materiales', function (Blueprint $table) {
            $table->unsignedInteger('usuario_id')->after('id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->date('fecha')->after('nro_remision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimiento_materiales', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropColumn(['usuario_id', 'fecha']);
        });
    }
};
