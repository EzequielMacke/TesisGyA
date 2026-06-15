<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE inventario MODIFY deposito_id INT UNSIGNED NULL');

        Schema::table('inventario', function (Blueprint $table) {
            $table->unsignedInteger('obra_id')->nullable()->after('deposito_id');
            $table->foreign('obra_id')->references('id')->on('obras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventario', function (Blueprint $table) {
            $table->dropForeign(['obra_id']);
            $table->dropColumn('obra_id');
        });

        DB::statement('ALTER TABLE inventario MODIFY deposito_id INT UNSIGNED NOT NULL');
    }
};
