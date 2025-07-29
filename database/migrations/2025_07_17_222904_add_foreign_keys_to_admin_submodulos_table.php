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
        Schema::table('admin_submodulos', function (Blueprint $table) {
            $table->foreign(['sub_modulo'], 'fk_sub_modulo')->references(['mod_id'])->on('admin_modulos')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_submodulos', function (Blueprint $table) {
            $table->dropForeign('fk_sub_modulo');
        });
    }
};
